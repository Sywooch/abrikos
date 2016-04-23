<?php

namespace frontend\modules\quiz\controllers;

use frontend\modules\quiz\models\Answer;
use frontend\modules\quiz\models\Quest;
use frontend\modules\quiz\models\Quiz;
use Imagick;
use ImagickPixel;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;

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
						'actions' => ['index','view', 'script' , 'draw', 'result'],
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
		$this->layout = 'console';
		return parent::init();
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
				$model = Quest::find()->joinWith(['quiz0'])->where(['quiz_quest.id' => $id, 'user' => Yii::$app->user->id])->one();
				$dimension = [800,600,5];
				break;
			case 'answer':
				$model = Answer::find()->joinWith(['quest0','quest0.quiz0'])->where(['quiz_answer.id' => $id, 'user' => Yii::$app->user->id])->one();
				$dimension = [100,100,1];
				break;
		}
		if(!isset($model)) throw new ForbiddenHttpException('Доступ запрещен');
		$dir = dirname($model->imagePath);
		if( !is_dir($dir) ) mkdir($dir,0775,true);
		if(!copy($file,$model->imagePath)) throw new HttpException(500,'Ошибка копирования');
		$this->doImage($model->imagePath,$dimension);
		return Json::encode(['image'=>$model->image]);
	}

	private function doImage($file,$dimension)
	{
		$image = new Imagick($file);
		$nW = $width = $image->getImageWidth();
		$nH = $height = $image->getImageHeight();
		$maxW = $dimension[0];$maxH=$dimension[1];$border=$dimension[2];
		if($width>$maxW || $height>$maxH) {
			if ($width > $height) {
				$nW = $maxW - $border;
				$nH = $height * $nW / $width - $border;
			} else {
				$nH = $maxH - $border;
				$nW = $width * $nH / $height - $border;
			}
			$image->thumbnailImage($nW, $nH);
		}
		$pointX =$maxW/2 - $nW/2;
		$pointY =$maxH/2 - $nH/2;
		$canvas = new Imagick();
		$canvas->newImage($maxW, $maxH, new ImagickPixel("gray"));
		$canvas->setImageFormat("jpeg");
		$canvas->compositeImage($image, Imagick::COMPOSITE_OVER, $pointX, $pointY);
		$canvas->writeImage($file);
		chmod($file,0664);
	}

	public function actionQuestSort($id)
	{
		$quiz=Quiz::findOne($id);
		$this->checkAccess($quiz->user);
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
		$answer = Answer::find()->joinWith(['quest0','quest0.quiz0'])->where(['quiz_answer.id' => $id, 'user' => Yii::$app->user->id])->one();
		$id = $answer->id;
		$answer->delete();
		return Json::encode(['id'=>$id]);
	}

	public function actionQuestUpdate($id)
	{
		$quest = Quest::find()->joinWith(['quiz0'])->where(['quiz_quest.id' => $id, 'user' => Yii::$app->user->id])->one();
		$quest->name = Yii::$app->request->post('name');
		$quest->save(true,['name']);
		return Json::encode($quest);
	}

	public function actionAnswerUpdate($id)
	{
		$answer = Answer::find()->joinWith(['quest0','quest0.quiz0'])->where(['quiz_answer.id' => $id, 'user' => Yii::$app->user->id])->one();
		$answer->text = Yii::$app->request->post('text');
		$answer->save(true,['text']);
		return Json::encode($answer);
	}

	public function actionQuestDelete($id)
	{
		$quest = Quest::find()->joinWith(['quiz0'])->where(['quiz_quest.id' => $id, 'user' => Yii::$app->user->id])->one();
		$id = $quest->id;
		$quest->delete();
		return Json::encode(['id'=>$id]);
	}


	public function actionAnswerAdd($id)
	{
		$quest = Quest::find()->joinWith(['quiz0'])->where(['quiz_quest.id'=>$id, 'user'=>Yii::$app->user->id])->one();
		$this->checkAccess($quest->quiz0->user);
		$answer = new Answer();
		$answer->quest = $quest->id;
		//$answer->text = 'Новый ответ';
		if(!$answer->save(true,['quest']))
			return Json::encode(['errors'=>$answer->errors]);
		return Json::encode($answer);
	}

	private function questlistFormater($quest)
	{
		return ['quest'=>$quest,'answers'=>$quest->answers,'questImage'=>$quest->image];
	}

	public function actionQuestList($id)
	{
		$quests = Quest::find()->joinWith(['answers','quiz0'])->where(['quiz'=>$id, 'user'=>Yii::$app->user->id])->orderBy('sort, quiz_quest.id ')->all();
		foreach ($quests as $quest) {
			$ret[] = $this->questlistFormater($quest);
		}
		return Json::encode($ret);
	}

	public function actionQuestAdd($id)
	{
		$quiz = Quiz::findOne($id);
		$this->checkAccess($quiz->user);
		$quest = new Quest();
		$quest->quiz = $quiz->id;
		//$quest->name = 'Новый Вопрос ' . substr(time(),7,3);
		$quest->save(true,['quiz']);
		return Json::encode($this->questlistFormater($quest));
	}
	
	public function actionCreate()
	{
		$quiz = new Quiz();
		$quiz->user = Yii::$app->user->id;
		//$quiz->name = 'Новая викторина';
		$quiz->save(true,['user']);
		$quest = new Quest();
		$quest->quiz = $quiz->id;
		//$quest->name = 'Новый Вопрос';
		$quest->save(true,['quiz']);
		$this->redirect('/quiz/update/'.$quiz->id);
	}

	public function actionUpdate($id)
	{
		$quiz = Quiz::findOne($id);
		$this->checkAccess($quiz->user);
		if ($quiz->load(Yii::$app->request->post()) ) {
			if(!$quiz->save()){
				return Json::encode(['errors'=>$quiz->errors]);
			}
			return Json::encode(['errors'=>[]]);
		}else{
			return $this->render('update', ['model' => $quiz,]);
		}

	}

	public function actionList()
	{
		$dataProvider = new ActiveDataProvider([
			'query'=> Quiz::find()->where(['user'=>Yii::$app->user->id])->orderBy('date desc')
		]);
		return $this->render('list',['dataProvider'=>$dataProvider]);
	}

	/**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

	private function checkAccess($user)
	{
		if($user !== Yii::$app->user->id) throw new ForbiddenHttpException('Доступ запрещен');
	}

}
