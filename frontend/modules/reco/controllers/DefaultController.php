<?php

namespace frontend\modules\reco\controllers;

use app\components\TopMenu;


use frontend\modules\reco\models\Quest;
use frontend\modules\reco\models\Tour;
use Imagick;
use Yii;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;


/**
 * Default controller for the `crea` module
 */
class DefaultController extends Controller
{

	public function init()
	{
		parent::init();
		TopMenu::addItems([
			['label'=>$this->module->name,'items'=>[
				['label'=>'Начало', 'url'=>$this->url()],
				['label'=>'Мои', 'url'=>$this->url('list')],
				['label'=>'Статистика', 'url'=>$this->url('stat')],
				['label'=>'Создать новую', 'url'=>$this->url('create'),'linkOptions'=>['data'=>['method'=>'post']]],
				['label'=>'Восстановление доступа', 'url'=>$this->url('restore')],
			]]
		]);
	}

	private function url($action='')
	{
		return '/'. $this->module->id.'/' . $action;
	}

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
					'delete' => ['post'],
					'create-quest' => ['post'],
					'create-foto' => ['post'],
					'sort' => ['post'],
					'upload' => ['post'],
				],
			],
		];
	}

	public function actionTour($id)
	{
		return $this->render('tour');
	}

	public function actionTourReload($id)
	{
		$session = Yii::$app->session[$this->module->id ];
		$session['answered'] = [];
		Yii::$app->session[$this->module->id ] = $session;
		return $this->actionTourData($id);
	}

	public function actionTourCurrent($id)
	{
		$model = $this->findTour($id);
		$this->checkAccess($model->quest0);
		return Json::encode($model->viewdata);
	}

	public function actionView($id,$tour=0)
	{
		$session = Yii::$app->session[$this->module->id ];
		if(!isset($session['quest-'.$id])){
			$session['quest-'.$id] = [];
			$session['quest-'.$id]['completed'] = [];
			Yii::$app->session[$this->module->id ] = $session;
		}
		//Yii::$app->session[$this->module->id ] = [];
		$model =Quest::findOne($id);
		return $this->render('view',['model'=>$model,'tour'=>$tour]);
	}

	public function actionTourNext($id)
	{
		$session = Yii::$app->session[$this->module->id ];
		if(!count($session['quest-'.$id]['completed'])) {
			$session['quest-' . $id]['completed'] = [];
			$session['quest-' . $id]['start'] = time();
			$session['quest-'.$id]['stop'] = 0;
			Yii::$app->session[$this->module->id ] = $session;
		}
		//$session['quest-' . $id]['completed'] = [];	Yii::$app->session[$this->module->id ] = $session;

		$query =Tour::find()
			->joinWith('quest0')
			->where(['not in','reco_tour.id',$session['quest-'.$id]['completed']])
			->andWhere('answer!="" and enabled>0')
			->andWhere(['reco_quest.id'=>$id])
			->orderBy('sort');
		$model = $query->one();
		//return $query->createCommand()->rawSql;
		if(!$model){
			$tours = Tour::find()
				->joinWith('quest0')
				->andWhere('answer!="" and enabled>0')
				->andWhere(['reco_quest.id'=>$id])
				->all();
			$ids = [];
			foreach ($tours as $t) $ids[] = $t->id;
			if(!count(array_diff($session['quest-'.$id]['completed'],$ids))){
				$session['quest-'.$id]['stop'] = time();
				$session['quest-' . $id]['completed'] = [];
				Yii::$app->session[$this->module->id ] = $session;
				$quest = $this->findQuest($id);
				$time = $this->module->timeString($session['quest-'.$id]['stop'] - $session['quest-'.$id]['start']);
				if($quest->email && $quest->sendmail){
					$response = new \stdClass();
					$response->email = $quest->email;
					//$link = 'https://' . $_SERVER['SERVER_NAME'] . '/'. $this->module->id.'/view/' . $model->id;
					$link = Yii::$app->urlManager->createAbsoluteUrl([$this->module->id . '/view', 'id' => $quest->id]);
					$response->subject = '' .  $this->module->name . '. Закончена игра ' .$quest->name;
					$response->body = 'Ваш адрес указан при создании сервиса "'. $this->module->name.'" '.$quest->name
						.' Получен верный ответ на созданное Вами задание. '
						." Затраченное время: $time "
						.$link;

					$this->module->mailToUser($response);
				}

				return Json::encode(['status'=>'completed', 'message'=>'Поздравляем! Игра "'.$quest->name.'" окончена!', 'time' => $time, 'link'=>$quest->link ]);
			}
			return Json::encode(['status'=>'empty', 'message'=>'Нет заданий']);
		}
		return Json::encode($model->viewdata);
	}

	public function actionQuestDelete($id)
	{
		$model = $this->findQuest($id);
		$this->checkAccess($model);
		if(!$model->delete()) throw new HttpException(500,$model->errors);
	}

		public function actionQuestPicture($id)
	{
		$model =$this->findTour($id);
		$this->checkAccess($model->quest0);
		$model->quest0->picture = $model->getImageName( Yii::$app->request->get('order') );
		if(!$model->quest0->save(true,['picture']))
			return Json::encode($model->quest0->errors);
		return Json::encode(['src'=>$model->quest0->image . '?' . time()]);

	}

	public function actionAnswer($id)
	{
		$model = $this->findTour($id);
		if(!$model) throw new HttpException(500,'Задание не найдено');
		$ret['status'] = 'wrong';
		if(mb_strtoupper($model->answer) == mb_strtoupper(Yii::$app->request->post('answer')) ){
			$ret['status'] = 'ok';
			$session = Yii::$app->session[$this->module->id];
			$session['quest-'.$model->quest]['completed'][] = $model->id;
			Yii::$app->session[$this->module->id] = $session;
			//$next = Json::decode($this->actionTourData($model->quest));
			//$ret['status'] = $next['status'];
			return Json::encode($ret);
		}
		return Json::encode($ret);
	}


	public function actionUpdate($id,$uid='')
	{
		$model = $this->findQuest($id);
		if($model->session == $uid && $uid){
			$session = Yii::$app->session;
			$session->set($this->module->id,['user'=>$model->session]);
		}
		$this->checkAccess($model);
		if($model->load(Yii::$app->request->post())){
			if(!$model->save(true,['name','email','published','sendmail']))
				return Json::encode(['status'=>'error', 'errors'=>$model->errors]);
		}
		if(!$model->email && !Yii::$app->user->isGuest)
			$model->email = Yii::$app->user->identity->email;
		return $this->render('update', ['model'=>$model]);
	}

	public function actionToursList($id)
	{
		$model = $this->findQuest($id);
		$this->checkAccess($model);
		$ret = [];
		foreach ($model->tours as $tour){
			$ret[] =$tour->formatter;
		}
		return Json::encode($ret);
	}
	
	public function actionCreate()
	{
		$model = new Quest();
		if(Yii::$app->user->isGuest) {
			if(!isset(Yii::$app->session[$this->module->id])){
				Yii::$app->session[$this->module->id] = [];
			}
			$session = Yii::$app->session[$this->module->id];
			if(isset($session['user'])) {
				$model->session = $session['user'];
				$model->user = 1;
			}else{
				$model->session = uniqid();
				$model->user = 1;
				$session['user'] = $model->session;
			}
			Yii::$app->session[$this->module->id] = $session;
		}else{
			$model->user = Yii::$app->user->id;
			$model->session = uniqid();
		}
		if($model->save(true,['user','session'])){
			$tour = new Tour();
			$tour->quest = $model->id;
			$tour->shuffle= '[]';
			$tour->save(true,['quest','shuffle']);
			$this->redirect($this->url('update/' . $model->id));

		}

		else
			throw new HttpException(500,VarDumper::export($model->errors));

	}

	public function actionTourCreate($id)
	{
		$model = new Tour();
		$model->quest = $id;
		$this->checkAccess($model->quest0);
		$last = Tour::find()->where(['quest'=>$id])->orderBy('sort desc')->one();
		$model->sort = $last->sort + 1;
		$model->shuffle = '[]';
		if(!$model->save(true,['shuffle','quest','sort'])) throw new HttpException(500, Json::encode($model->errors));
		return Json::encode($model->formatter);
	}


	public function actionImages($id)
	{
		$model = $this->findTour($id);
		return Json::encode($model->images);
	}

	public function actionChoosenDownload($id)
	{
		//print_r($_POST);exit;
		$model = $this->findTour($id);
		$this->checkAccess($model);
		if(is_array($src = Yii::$app->request->post('src'))){
			foreach ($src as $i=>$s){
				if(preg_match('!^http!',$s)) copy($s,$model->getImagePath($i));
			}
		}
	}

	public function actionList()
	{
		$session = Yii::$app->session[$this->module->id];
		if(!isset($session['user'])) {
			$session['user'] = uniqid();
		}
		Yii::$app->session[$this->module->id] = $session;
		$where = Yii::$app->user->isGuest ? ['session'=>$session['user']] : ['user'=>Yii::$app->user->id];
		$dataProvider = new ActiveDataProvider([
			'query'=> Quest::find()->where($where)->orderBy('date desc')
		]);
		return $this->render('list',['dataProvider'=>$dataProvider]);
	}
	
	public function actionPublicate($id)
	{
		$model = $this->findTour($id);
		$this->checkAccess($model->quest0);
		$model->enabled = (!$model->enabled)*1;
		$model->save(true,['enabled']);
		return Json::encode(['enabled'=>$model->enabled*1, 'errors'=>$model->errors]);
	}

	private static function cmp_by_optionNumber($a, $b) {
		return $a['tour']["sort"] - $b['tour']["sort"];
	}


	public function actionTourSort($id)
	{
		$model = Quest::findOne($id);
		$this->checkAccess($model);
		foreach (Yii::$app->request->post('sort') as $sort){
			$sorts[$sort['id']] = $sort['order'];
		}
		$ret = [];
		foreach ($model->tours as $tour) {
			$tour->sort = $sorts[$tour->id];
			if(!$tour->save(true,['sort'])){
				throw new HttpException(500,VarDumper::export($model->errors));
			}
			$ret[] =$tour->formatter;
		}
		usort($ret, array($this::className(),"cmp_by_optionNumber"));
		return Json::encode($ret);
	}

	public function actionTourUpdate($id)
	{
		$model = $this->findTour($id);
		$this->checkAccess($model->quest0);
		if ($model->load(Yii::$app->request->post()) ) {
			$letter = '';
			for ($i=0; $i<$model->letters_count; $i++){
				//$rand = $model->eng ? 90 : 0xC0;
				$rand = $model->eng ? rand(65,90) : rand(0xC0,0xDF);
				$letter .= iconv('CP1251', 'UTF-8', chr($rand));
			}
			$model->letters = $letter;
			$shuffle = preg_split('//u',mb_strtoupper($model->letters.$model->answer), -1, PREG_SPLIT_NO_EMPTY);
			shuffle($shuffle);
			$model->shuffle = Json::encode( $shuffle);
			if(!$model->save()){
				return Json::encode(['errors'=>$model->errors]);
			}
			return Json::encode($model->formatter);
		}else{
			throw new HttpException(500,'Ошибка в полях формы');
		}
	}

	public function actionSearch()
	{
		if(!$word = Yii::$app->request->post('word')){
				$text = iconv('Windows-1251','UTF-8',file_get_contents('http://linorgoralik.com/randomword.php'));
				preg_match('!<body>(.*?)</body>!s',$text,$match);
				//print '<plaintext>';print_r($match);exit;
				$word = trim($match[1]);
		}
		$url = 'https://www.bing.com/images/search?count=100&q=' . urlencode($word);
		$content = file_get_contents($url);
		preg_match_all('!<img .*?src="http(.*?)".*?>!',$content,$matches);
		for ($i=1;$i<count($matches[1]);$i++) $ret['images'][] = ['id'=>md5($matches[1][$i]), 'src'=>'http'.$matches[1][$i]];
		$ret['images'][] = ['id'=>'bing-logo', 'src'=>'/images/bing.jpg'];
		$ret['word']=$word;
		//print '<plaintext>';print_r($ret);exit;
		return Json::encode($ret);
	}

	public function actionSearchDebug()
	{
		return '{"images":[{"id":"55ae4b2b42efc6d197bf6afc15c1afea","src":"https://tse1.mm.bing.net/th?id=OIP.Mc9a00296e7a2dfea8d89ab42db207408o2&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"ceae2d1a60bcabc64189f1618a60cd69","src":"https://tse3.mm.bing.net/th?id=OIP.Mef76045af8435d53bae2042db9932c4fo0&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"45d1ab35d340fd5c92819d62cba135e7","src":"https://tse2.mm.bing.net/th?id=OIP.M5084b51f8a74f0536d8649a5c9d15c31o0&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"85f4400f6c01671068dd25bd736b79de","src":"https://tse3.mm.bing.net/th?id=OIP.M44f18a19851b760b451910d5cb827345o0&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"2d42874b15e98f7d6560cf2e78320edc","src":"https://tse3.mm.bing.net/th?id=OIP.Mfa03380af3cd6510ece09f2a0458b75bo0&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"64b7a85402aaa081f496579dcb481447","src":"https://tse2.mm.bing.net/th?id=OIP.Mc38968a36d403b856d07c7f68a4187fbo0&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"2b11d0d5fdddc314b837886dc5be3e77","src":"https://tse4.mm.bing.net/th?id=OIP.M33e02b6a882172ecaa771593d2449aefo0&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"48e65a3bf02b9d7c629466a90d18d409","src":"https://tse3.mm.bing.net/th?id=OIP.M356d72d05bdc5e3efff40e3fdc0d2a58o0&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"962cd5dfeb8d5ebe44c7a3d48ffb4c5e","src":"https://tse1.mm.bing.net/th?id=OIP.M5c9dafebcddab5fe3ad11827613ffb6bo0&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"70e5122de1e6a27c57384855b86b1dd6","src":"https://tse3.mm.bing.net/th?id=OIP.M70583269131fdc26f3a192d66e008bdco0&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"2cddf545fe18c45623fb589c854053ba","src":"https://tse1.mm.bing.net/th?id=OIP.M74c5dd5fc333ce91fdbdaab47e89943co2&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"f669a7e069efd38dc813fe8245e614b6","src":"https://tse3.mm.bing.net/th?id=OIP.M82f4e61f2403094eb3f51c9bf48958d8o0&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"88ec7fdd1128fc3715277558d9af6c9c","src":"https://tse3.mm.bing.net/th?id=OIP.Mdb155b3d0dad0c3e2a037f9fed9c9e1fo0&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"3a85165c1b76d3f5d9c1d4721ed2f285","src":"https://tse2.mm.bing.net/th?id=OIP.M9179284ff9e5fdeb023d5798b150bb30o0&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"4351b66a936ea679301abad1ae2300e5","src":"https://tse1.mm.bing.net/th?id=OIP.M8e9126ab97e05b553cd76788cebb7ae1o0&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"a0e81b454dd01b656caa46cc60b9ad5a","src":"https://tse1.mm.bing.net/th?id=OIP.M82d4ba4824cae4d31167f853fc98b23eo0&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"989f76e6f1d6503045f401a4b6cfedb5","src":"https://tse3.mm.bing.net/th?id=OIP.M55ed6148e43a601dbfc519378bddaa02o0&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"9e711b66d46f9b926896916180590a37","src":"https://tse2.mm.bing.net/th?id=OIP.M4ecdacd120d4484a625fe83868ea1630o0&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"c25947b7fda93f39b67fa0e00433a896","src":"https://tse2.mm.bing.net/th?id=OIP.M9323f2611e0894a5529d8682777d190co0&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"a29dcc66164b7cf8e1098485fa421d85","src":"https://tse4.mm.bing.net/th?id=OIP.M99b23a28fa200c34192e7884b73a913fo0&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"ea31c1791c4e46e13bc1c3238e1cef95","src":"https://tse4.mm.bing.net/th?id=OIP.Me5191d3575d41013eaffeef55e36c16eo0&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"5b4958c3d1b68b5a618806a02756c4fa","src":"https://tse1.mm.bing.net/th?id=OIP.Mf200179f8457a29e7cfd7c655a7c50e3o0&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"6ba165e6acde689097ee57794303f785","src":"https://tse4.mm.bing.net/th?id=OIP.Ma5e3db4b80b13043bdef5d190db4b392o0&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"de78844252298ddcbe7bb0684b8dfbf7","src":"https://tse1.mm.bing.net/th?id=OIP.Mb0d1dd10d35ee649adfe67f030c5b2d3o0&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"bddff6b776e5dc664fbfe18b96bbfd2d","src":"https://tse4.mm.bing.net/th?id=OIP.Medb1bef6fd4bd09f4c90614ab67d36f1o0&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"9cf981b724d007aa3283db5546983f0e","src":"https://tse1.mm.bing.net/th?id=OIP.M0150b8c85e186cfad9cf6fc1edb04b25o0&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"d8ff6d7b6629396206eeebd3cd4680a1","src":"https://tse2.mm.bing.net/th?id=OIP.M216f80f8f4ad9df507bf787f54fc95aao0&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"37f12ee55845fa02e81325165123ee5c","src":"https://tse2.mm.bing.net/th?id=OIP.M60c9f50211b96a643f76d4c81e9c3a52o0&w=230&h=170&rs=1&pcl=dddddd&pid=1.1"},{"id":"656bd4d5811c1250dd7c67e5badd8660","src":"/rms/rms%20answers%20Shared%20Feedback$bubble/ic/4907366b/da274d75.png"}],"word":"спасении"}';
	}

    public function actionIndex()
    {
	    return $this->render('index');
    }

	private function checkAccess($model)
	{
		if(Yii::$app->user->can('moderator')) return true;
		$error = false;
		$message = 'Доступ запрещен';
		if(Yii::$app->user->isGuest){
			$session = Yii::$app->session[$this->module->id];
			//print $model->session ; print ' - '; print_r( $session);exit;
			if(!isset($session['user'])) $session['user'] = uniqid();
			if($session['user']!=$model->session){
				$error = true;
				Yii::$app->session->setFlash('info',  'Попробуйте '.Html::a('Восстановить доступ к игре', '/'. $this->module->id.'/restore/'.$model->id,['class'=>'btn btn-primary']));
			}
		}elseif($model->user !== Yii::$app->user->id){
			$error = true;
		}
		if($error){
			throw new ForbiddenHttpException( $message . $model->user . Yii::$app->user->id );
		}
	}

	public function actionUpload($id,$type,$index)
	{
		//print_r($_FILES);exit;
		switch ($type){
			case 'disk':
				if($_FILES['file']['error']) throw new HttpException(500,'Ошибка загрузки файла. ');
				if(!preg_match('!^image!', $_FILES['file']['type'])) throw new HttpException(500,'Разрешена загрузка только изображений! ' . $_FILES['file']['type']);
				$file = $_FILES['file']['tmp_name'];
				break;
			case 'remote':
				$file = Yii::$app->request->post('url');
				break;
			default:
				throw new HttpException(500,'wrong $type');
		}
		try {
			$image = getimagesize($file);
		}catch (\Exception $e){
			throw new HttpException(500,'Разрешена загрузка только изображений. ' . $e->getMessage());
		}
		if(!preg_match('!^image!', $image['mime'])) throw new HttpException(500,'Разрешена загрузка только изображений. 2');

		$model = $this->findTour($id);
		$this->checkAccess($model->quest0);

		if(!isset($model)) throw new NotFoundHttpException('Объект не найден');
		$dir = dirname($model->getImagePath($index));
		if( !is_dir($dir) ) mkdir($dir,0775,true);
		if(!copy($file,$model->getImagePath($index))) throw new HttpException(500,'Ошибка копирования');
		$this->doImage($model->getImagePath($index));
		return Json::encode(['image'=>$model->getImageUrl($index), 'imageid'=>$model->getImageId($index)]);
	}

	private function doImage($file)
	{
		$maxW = $this->module->maxWidth;
		$maxH = $this->module->maxHeight;
		$image = new Imagick($file);
		$width = $image->getImageWidth();
		$height = $image->getImageHeight();
		if ($width > $maxW || $height>$maxH) {
			if ($maxW > $maxH) {
				$nW = $maxW;
				$nH = $height * $nW / $width;
			} else {
				$nH = $maxH;
				$nW = $width * $nH / $height;
			}
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

	private function findTour($id)
	{
		$model = Tour::find()->joinWith('quest0')->where(['reco_tour.id'=>$id])->one();
		if(!$model) throw new NotFoundHttpException('Не найдено');
		return $model;
	}

	private function findQuest($id)
	{
		$model = Quest::find()->joinWith('tours')->where(['reco_quest.id'=>$id])->one();
		if(!$model) throw new NotFoundHttpException('Не найдено');
		return $model;
	}

}
