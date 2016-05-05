<?php

namespace frontend\modules\quiz\controllers;

use app\components\TopMenu;
use frontend\modules\quiz\models\Answer;
use frontend\modules\quiz\models\Quest;
use frontend\modules\quiz\models\Quiz;
use frontend\modules\quiz\models\Result;
use Imagick;
use Yii;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Session;

/**
 * Default controller for the `quiz` module
 */
class DefaultController extends Controller
{
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						//'actions' => ['index','view', 'script' , 'draw', 'result'],
						'allow' => true,
					],
					[
						'allow' => true,
						'roles' => ['user'],
					],
				],
			],

			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'answer-delete' => ['post'],
					'delete' => ['post'],
					'create' => ['post'],
					'sort' => ['post'],
					'upload' => ['post'],
				],
			],
		];
	}

	public function init()
	{
		parent::init();
		//$this->layout ='console';
		TopMenu::addItems([
			['label'=>'Викторины','items'=>[
				['label'=>'Начало', 'url'=>'/quiz'],
				['label'=>'Мои викторины', 'url'=>'/quiz/list'],
				['label'=>'Статистика', 'url'=>'/quiz/stat'],
				['label'=>'Создать свою викторину', 'url'=>'/quiz/create','linkOptions'=>['data'=>['method'=>'post']]],
				['label'=>'Восстановление доступа', 'url'=>'/quiz/restore'],
			]]
		]);
		Yii::$app->errorHandler->errorAction = 'quiz/default/error';
	}

	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
		];
	}

	public function actionError($message='')
	{
		return $this->render('process/error',['message'=>$message]);
	}


	public function actionConsoleResult($round)
	{
		$results = Result::findAll(['round'=>$round]);
		if(!count($results)) throw new HttpException(500,'Не найдено такого раунда:' . $round);
		return $this->render('console/result',['results'=>$results]);
	}
	
	public function actionProcessDone($id,$round)
	{
		//$this->layout = 'process';
		$quiz = Quiz::findOne($id);
		if(!$quiz) throw new HttpException(500,'Викторина не найдена');
		return $this->render('process/done',['quiz'=>$quiz, 'round'=>$round]);
	}

	private function resultAdd($quest,$answer,$date,$email,$round)
	{
		if(!($quest && $answer && $round)) return "Все параметры обязательны.  quest=$quest  answer=$answer  round=$round";
		$model = new Result();
		$model->quest = $quest;
		$model->answer = $answer;
		$model->round = $round;
		$model->date = $date;
		$model->email = $email;
		$model->ip = $_SERVER['REMOTE_ADDR'];
		if(!$model->save()){
			throw new HttpException(500,"Результат не сохранен quest=$quest  answer=$answer  round=$round ". VarDumper::export($model->errors));
		}
	}

	public function actionProcessView($id)
	{
		$quiz = Quiz::findOne($id);
		if(!$quiz) throw new HttpException(500,'Викторина не найдена');
		if(!$quiz->quests) throw new HttpException(500,'У этой викторины нет вопросов');
		$session = new Session;
		$session->open();
		if(!isset($session['quiz-'.$id])){$session['quiz-'.$id] = []; }
		$quiz_session = $session['quiz-'.$id];
		if(!isset($quiz_session)){
			//print_r($completed[$id]);exit;
			//$model = Quest::find()->joinWith(['answers'])->where('quiz='.$id )->orderBy('sort')->one();
			//$quiz_session['quests-done'] = [0];
			//$session['quiz'] = $quiz_session;
		}else{
			$query = Quest::find()->joinWith(['answers'])->where(['quiz'=>$id])->andWhere(['not in','quiz_quest.id',array_keys($quiz_session)])->orderBy('sort');
			//print $query->createCommand()->rawSql;exit;
			$model = $query->one();
		}
		if(!isset($model)){
			//print '<plaintext>';print_r($quiz_session);exit;
			$round = uniqid();
			foreach ($quiz_session as $quest=>$answer) {
				//print '<plaintext>';print_r($answer);exit;
				if(!$quest) continue;
				foreach ($answer['id'] as $aid){
					$this->resultAdd($quest,$aid,$answer['time'],$answer['email'],$round);
				}
			}
			$mailData = new \stdClass();
			if($quiz->send_results && $mailData->email = $quiz->findEmail()) {
				$link = 'http://' . $_SERVER['SERVER_NAME'] . '/quiz/console-result?round=' . $round;
				$mailData->subject = 'Ответы на викторину #' . $quiz->id . '. ';
				$mailData->body = 'Викторина "' . $quiz->name . '". Получены ответы. Для просмотра перейдите по ссылке ' . $link;
				$this->mailToUser($mailData);
			}
			$session['quiz-'.$id] = [];
			return Json::encode(['status'=>'done','round'=>$round, 'session'=>$quiz_session]);
		}
		return Json::encode($this->questlistFormater($model,0));
	}


	public function actionStat()
	{
		return $this->render('stat');
	}
	
	public function actionProcessAnswer($id)
	{
		$quest = Quest::findOne($id);
		if(!$quest) throw new HttpException(500,'Вопрос не найден');
		$answers = Yii::$app->request->post('answers');
		if(!$answers) return Json::encode(['status'=>'error','message'=>'Необходимо выбрать ответ!']);
		$session = new Session;
		$session->open();
		$quiz_session = $session['quiz-'.$quest->quiz];
		if(!isset($quiz_session[$quest->id])) {
			$quiz_session[$quest->id] = ['id'=>$answers,'time'=>date('Y-m-d H:i:s') , 'email'=>Yii::$app->request->post('email')];
			$session['quiz-'.$quest->quiz] = $quiz_session;
			if (in_array($quest->quiz0->show_result, [2, 3])) {
				return Json::encode(['status' => 'show_result', 'answers' => $quest->answers, 'description'=>$quest->description]);
			}
		}
		return $this->actionProcessView($quest->quiz);
	}

	public function actionView($id, $tour)
	{
		//$this->layout = 'process';
		$quiz = Quiz::findOne($id);
		if(!$quiz->enabled) return $this->render('process/error',['message'=>'Владелец викторины отключил её' ]);
		$noanswer = $quiz->noAnswers();
		if($noanswer)	return $this->render('process/error',['message'=>'Вопрос "' .$noanswer->name . '" не имеет ответа.' ]);
		
		Yii::$app->view->registerMetaTag(['property'=>'og:title', 'content'=>$quiz->name, 'id'=>'og-title'], 'og-title');
		Yii::$app->view->registerMetaTag(['property'=>'og:description', 'content'=>'Викторина. '.$quiz->description], 'og-desc');

		Yii::$app->view->registerMetaTag(['property' => 'og:image', 'content' => 'http://'.$_SERVER['SERVER_NAME'].$quiz->image],'og-image');
		Yii::$app->view->registerMetaTag(['property' => 'og:image:secure_url', 'content' => 'http://'.$_SERVER['SERVER_NAME'].$quiz->image],'og-imagesec');
		Yii::$app->view->registerMetaTag(['property' => 'og:image:width', 'content' => 400],'og-image-width');
		Yii::$app->view->registerMetaTag(['property' => 'og:image:height', 'content' => 200],'og-image-height');
		Yii::$app->view->registerLinkTag(['rel' => 'image_src', 'href' => 'http://'.$_SERVER['SERVER_NAME'].$quiz->image],'og-image-rel');

		return $this->render('process/view',['model'=>$quiz]);

	}

	public function actionAnswerCorrect($id)
	{
		$answer = Answer::find()->joinWith(['quest0','quest0.quiz0'])->where(['quiz_answer.id' => $id])->one();
		$this->checkAccess($answer->quest0->quiz0);
		$answer->correct = $answer->correct ? 0 : 1;
		$answer->save(true,['correct']);
		return Json::encode($answer);
	}

	public function actionAnswerText($id)
	{
		$answer = Answer::find()->joinWith(['quest0','quest0.quiz0'])->where(['quiz_answer.id' => $id])->one();
		$this->checkAccess($answer->quest0->quiz0);
		$answer->media = 0;
		@unlink($answer->imagePath);
		$answer->save(true,['media']);
		return Json::encode($answer);
	}

	public function actionUpload($id,$type,$object)
	{
		switch ($type){
			case 'disk':
				if($_FILES['file']['error']) throw new HttpException(500,'Ошибка загрузки файла');
				if(!preg_match('!^image!', $_FILES['file']['type'])) throw new HttpException(500,'Разрешена загрузка только изображений');
				$file = $_FILES['file']['tmp_name'];
				break;
			case 'remote':
				$file = Yii::$app->request->post('url');
				break;
		}
		$image=@getimagesize($file);
		if(!$image) throw new HttpException(500,'Разрешена загрузка только изображений. 1' . VarDumper::export($_POST));
		if(!preg_match('!^image!', $image['mime'])) throw new HttpException(500,'Разрешена загрузка только изображений. 2');

		switch ($object){
			case 'quest':
				$model = Quest::find()->joinWith(['quiz0'])->where(['quiz_quest.id' => $id])->one();
				$this->checkAccess($model->quiz0);
				$maxWidth = 600;
				break;
			case 'answer':
				$model = Answer::find()->joinWith(['quest0','quest0.quiz0'])->where(['quiz_answer.id' => $id])->one();
				$this->checkAccess($model->quest0->quiz0);
				$model->media = 1;
				$model->save(true,['media']);
				$maxWidth = 200;
				break;
			case 'quiz':
				$model = Quiz::findOne($id);
				$this->checkAccess($model);
				$maxWidth = 400;
				break;
			default:
				return;
		}
		if(!isset($model)) throw new NotFoundHttpException('Объект не найден');
		$dir = dirname($model->imagePath);
		if( !is_dir($dir) ) mkdir($dir,0775,true);
		if(!copy($file,$model->imagePath)) throw new HttpException(500,'Ошибка копирования');
		$this->doImage($model->imagePath,$maxWidth);
		return Json::encode(['image'=>$model->image]);
	}

	private function doImage($file,$maxW)
	{
		$image = new Imagick($file);
		$width = $image->getImageWidth();
		$height = $image->getImageHeight();
		if($width>$maxW) {
			$nW = $maxW;
			$nH = $height * $nW / $width;
			$image->thumbnailImage($nW, $nH);
			$image->writeImage($file);
		}
		/*
		$pointX =$maxW/2 - $nW/2;
		$pointY =$maxH/2 - $nH/2;
		$canvas = new Imagick();
		$canvas->newImage($maxW, $maxH, new ImagickPixel("cornflowerblue"));
		$canvas->setImageFormat("jpeg");
		$canvas->compositeImage($image, Imagick::COMPOSITE_OVER, $pointX, $pointY);
		$canvas->writeImage($file);
		*/

		chmod($file,0664);
	}

	public function actionQuestSort($id)
	{
		$quiz=Quiz::findOne($id);
		$this->checkAccess($quiz);
		foreach (Yii::$app->request->post('sort') as $order){
			$a = Quest::findOne($order['id']);
			$a->sort = $order['order'];
			if(!$a->save(true,['sort'])){
				print_r($a->errors);
			}
			print "$order[id] - $order[order]\n";
		}
	}


	public function actionAnswerDelete($id)
	{
		$answer = Answer::find()->joinWith(['quest0','quest0.quiz0'])->where(['quiz_answer.id' => $id])->one();
		$this->checkAccess($answer->quest0->quiz0);
		if(!$answer)
			return Json::encode(['error'=>'Такой ответ не существует','id'=>$id]);
		try{
			$answer->delete();
		}catch ( Exception $e){
			return Json::encode(['error'=>'Невозможно удалить этот вариант ответа. Есть ответившие',$e]);
		}
		return Json::encode(['id'=>$answer->id]);
	}

	public function actionQuestUpdate($id)
	{
		$quest = Quest::find()->joinWith(['quiz0'])->where(['quiz_quest.id' => $id])->one();
		$this->checkAccess($quest->quiz0);
		$quest->name = Yii::$app->request->post('name');
		$quest->description = Yii::$app->request->post('description');
		//$quest->description = "hello' insert into quiz_result (quest) value (1)";
		$quest->multi = Yii::$app->request->post('multi')*1;
		if(!$quest->save(false,['name','multi','description'])) throw new HttpException(500,VarDumper::export($quest->errors));
		return Json::encode($quest);
	}

	public function actionAnswerUpdate($id)
	{
		$answer = Answer::find()->joinWith(['quest0','quest0.quiz0'])->where(['quiz_answer.id' => $id])->one();
		$this->checkAccess($answer->quest0->quiz0);
		$answer->text = Yii::$app->request->post('text');
		$answer->save(true,['text']);
		return Json::encode($answer);
	}

	public function actionQuestDelete($id)
	{
		$quest = Quest::find()->joinWith(['quiz0'])->where(['quiz_quest.id' => $id])->one();
		$this->checkAccess($quest->quiz0);
		if(!$quest)
			return Json::encode(['error'=>'Такой вопрос не существует','id'=>$id]);
		try{
			$quest->delete();
		}catch ( Exception $e){
			return Json::encode(['error'=>'Невозможно удалить этот вопрос. Есть ответившие']);
		}
		return Json::encode(['id'=>$quest->id]);
	}

	public function actionCheck($id)
	{
		$quiz = Quiz::findOne($id);
		return Json::encode($quiz->noAnswers());
	}

	public function actionAnswerAdd($id)
	{
		$quest = Quest::find()->joinWith(['quiz0'])->where(['quiz_quest.id'=>$id])->one();
		$this->checkAccess($quest->quiz0);
		$answer = new Answer();
		$answer->quest = $quest->id;
		//$answer->text = 'Новый ответ';
		if(!$answer->save(true,['quest']))
			return Json::encode(['errors'=>$answer->errors]);
		return Json::encode($answer);
	}

	private function questlistFormater($quest,$admin)
	{
		$answers = $questModel = [];
		$a_fields = ['id','text','quest','media','image'];
		$q_fields = ['id','name','quiz','multi','image', 'description'];
		if($admin){
			$a_fields = array_merge($a_fields, ['correct']);
			$q_fields = array_merge($q_fields, ['sort']);
			$answerList = $quest->answers;
		}else{
			$answerList = $quest->answersRand;
		}
		foreach ($q_fields as $f){
			$questModel[$f] = $quest->$f;
		}
		foreach ($answerList as $answer) {
			foreach ($a_fields as $f){
				$record[$f] = $answer->$f;
			}
			$answers[] = $record;
		}
		return ['quest'=>$questModel,'answers'=>$answers,'admin'=>$a_fields];
	}

	public function actionQuestList($id)
	{
		$quiz = Quiz::find()->joinWith(['quests','quests.answers'])->where(['quiz_quiz.id'=>$id])->one();
		$this->checkAccess($quiz);
		$ret = [];
		foreach ($quiz->quests as $quest) {
			$ret[] = $this->questlistFormater($quest,1);
		}
		return Json::encode($ret);
	}

	public function actionQuestAdd($id)
	{
		$quiz = Quiz::findOne($id);
		$this->checkAccess($quiz);
		$quest = new Quest();
		$quest->quiz = $quiz->id;
		//$quest->name = 'Новый Вопрос ' . substr(time(),7,3);
		$quest->save(true,['quiz']);
		return Json::encode($this->questlistFormater($quest,1));
	}
	
	public function actionCreate()
	{
		$quiz = new Quiz();
		if(Yii::$app->user->isGuest){
			$quiz->sessionId = uniqid();
			$quiz->user = 1;
			$session = new Session();
			$session->open();
			$session['user'] = $quiz->sessionId;
		}else{
			$quiz->user = Yii::$app->user->id;
		}
		//$quiz->name = 'Новая викторина';
		if(!$quiz->save(true,['user','sessionId'])) return print_r($quiz->errors);
		$quest = new Quest();
		$quest->quiz = $quiz->id;
		//$quest->name = 'Новый Вопрос';
		$quest->save(true,['quiz']);
		$this->redirect('/quiz/update/'.$quiz->id);
	}

	public function actionDelete($id)
	{
		$quiz = Quiz::findOne($id);
		$this->checkAccess($quiz);
		try{
			$quiz->delete();
		}catch (Exception $e){
			Yii::$app->session->setFlash('info',Html::a('Вернуться к редактированию', '/quiz/update/'.$id,['class'=>'btn btn-primary']));
			throw new HttpException(500,'не возможно удалить викторину. Есть ответы.');
		}

		$this->redirect('/quiz/list');
	}

	public function actionUpdate($id,$uid='')
	{
		$quiz = Quiz::findOne($id);
		if($quiz->sessionId == $uid && $uid){
			$session = new Session();
			$session['user']=$quiz->sessionId;
		}
		$this->checkAccess($quiz);
		if ($quiz->load(Yii::$app->request->post()) ) {
			if(!$quiz->save()){
				return Json::encode(['errors'=>$quiz->errors]);
			}
			return Json::encode(['errors'=>[]]);
		}else{
			return $this->render('console/update', ['model' => $quiz,]);
		}

	}

	public function actionList()
	{
		$session = new Session();
		$session->open();
		$where = Yii::$app->user->isGuest ? ['sessionId'=>$session['user']] : ['user'=>Yii::$app->user->id];
		$dataProvider = new ActiveDataProvider([
			'query'=> Quiz::find()->where($where)->orderBy('date desc')
		]);
		return $this->render('console/list',['dataProvider'=>$dataProvider]);
	}

	/**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

	private function checkAccess($quiz)
	{
		$error = false;
		$message = 'Доступ запрещен';
		if(Yii::$app->user->isGuest){
			$session = new Session();
			$session->open();
			if($session['user']!=$quiz->sessionId){
				$error = true;
				Yii::$app->session->setFlash('info',  'Попробуйте '.Html::a('Восстановить доступ к викторине', '/quiz/restore/'.$quiz->id,['class'=>'btn btn-primary']));
			}
		}elseif($quiz->user !== Yii::$app->user->id){
			$error = true;
		}
		if($error){
			throw new ForbiddenHttpException( $message );
		}
	}

	private function mailToUser($data)
	{
		$mailer = Yii::$app->mailer->compose()
			->setFrom(['abrikoscron@gmail.ru' => \Yii::$app->name . '. Робот'])
			->setTo($data->email)
			->setSubject($data->subject)
			->setTextBody($data->body ." \n" .Yii::$app->name . " \n" . $_SERVER['SERVER_NAME']);
		if(!$mailer->send()){
			throw new HttpException(500,'E-mail не отправлен. Обратитесь в тех-поддержку разделе "Помошь"');
		}
	}

	public function actionRestore($id='')
	{
		return $this->render('restore',['id'=>$id]);
	}

	public function actionRestoreStart()
	{
		$code = Yii::$app->request->post('code');
		$quiz = Quiz::findOne($code);
		if(!$quiz){
			Yii::$app->session->setFlash('error','Викторина на найдена');
			return $this->render('restore-result');
		}
		if(!$quiz->email){
			Yii::$app->session->setFlash('error','При создании викторины #'.$code.' не был указан e-mail для восстановления доступа. Попробуйте обратиться к админитрации.');
			return $this->redirect('/site/contact');
		}
		if ($quiz->user > 1 &&  Yii::$app->user->isGuest) {
			Yii::$app->session->setFlash('warning', 'Викторина принадлежит зарегистрированному пользователю. Пожалуйста выполните вход и найдите свою викторину в <a href="/quiz/list">списке</a>');
			return $this->redirect('/site/login');
		}
		if ($quiz->user !=  Yii::$app->user->id && !Yii::$app->user->isGuest) {
			Yii::$app->session->setFlash('error','Викторина #'.$code.' принадлежит другому пользователю');
			return $this->render('restore',['id'=>'']);
		}
		$response = new \stdClass();
		$response->email = $quiz->email;
		$link = 'http://' . $_SERVER['SERVER_NAME'] . '/quiz/restore-finish?uid=' . $quiz->sessionId;
		$response->subject = 'Запрос восстановлени доступа к Викторине. ';
		$response->body = 'Ваш адрес указан при создании викторины "'.$quiz->name.'". Было инициировано восстановление доступа к викторине. Если нет уверенности, что это произошло по Вашему запросу, то можете не обращать внимание на это сообщение. Для продолжения восстановления викториныпройдите по ссылке '.$link;
		$this->mailToUser($response);
		Yii::$app->session->setFlash('success','На почту, указанную при создании викторины, отправлены инструкции ');
		return $this->render('restore-result');
	}

	public function actionRestoreFinish($uid)
	{
		$quiz = Quiz::findOne(['sessionId'=>$uid]);
		if(!$quiz) throw new NotFoundHttpException('Викторина не найдена');
		$quiz->sessionId = uniqid();
		$quiz->save(true,['sessionId']);
		$response = new \stdClass();
		$response->email = $quiz->email;
		$link = 'http://' . $_SERVER['SERVER_NAME'] . '/quiz/update/'.$quiz->id.'?uid=' . $quiz->sessionId;
		$response->subject = 'Викторина #'.$quiz->id.' восстановлена. ';
		$response->body = 'Доступ к викторине "'.$quiz->name . '" восстановлен. Перейдите по ссылке '.$link;
		$this->mailToUser($response);
		Yii::$app->session->setFlash('success','Инструкции по завершению восстановления викторины "'.$quiz->name.'" отправлены на e-mail ');
		return $this->render('restore-result');
	}

}
