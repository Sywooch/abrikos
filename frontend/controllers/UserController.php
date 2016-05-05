<?php

namespace frontend\controllers;

use common\models\Openid;
use common\models\Ulogin;
use common\models\User;
use Yii;
use yii\helpers\Json;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\YiiAsset;

class UserController extends \yii\web\Controller
{
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['ulogin-add', ],
						'allow' => true,
					],
					[
						//'actions' => ['cabinet', ],
						'allow' => true,
						'roles' => ['admin','user'],
					],
					[
						'actions' => ['cabinet', 'ulogin-list', 'ulogin-delete', 'password-change', 'email-change'],
						'allow' => true,
						'roles' => ['user'],
					],


				],
			],

			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
				],
			],
		];
	}

	public function actionCabinet()
	{
		return $this->render('cabinet');
	}

	public function actionUloginDelete($id){
		Ulogin::findOne(['id'=>$id, 'user'=>\Yii::$app->user->id])->delete();
	}


	public function actionPasswordUpdate()
	{
		$error = 0;
		$model = Yii::$app->user->identity;
		if ($model->load(Yii::$app->request->post()) ) {
			//return $model->validatePassword($model->oldpassword);
			if($model->validate(true,['oldpassword','password','password_retry']))
			{
				$model->setPassword($model->password);
				$model->generateAuthKey();
				if(!$model->save(true,['password_hash','auth_key'])){$error =$model->errors;	}

			}else{
				$error =$model->errors;
			}

		}
		return Json::encode(['error'=>$error]);

	}

	public function actionCardUpdate()
	{
		$error = 0;
		$model = Yii::$app->user->identity;
		if ($model->load(Yii::$app->request->post()) ) {
			if(!$model->save(true,['first_name','last_name'])) $error =$model->errors;
		}
		return Json::encode(['error'=>$error]);
	}

	public function actionEmailChange(){
		return;
		$errors = [];
		$user = User::findOne(\Yii::$app->user->id);
		$user->email = Yii::$app->request->post('email');
		if(!$user->save(true,['email'])) $errors = $user->errors;
		return Json::encode(['error'=>$errors]);
	}


	public function actionUloginAdd()
	{
		$s = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
		$user = json_decode($s, true);
		//$user['network'] - соц. сеть, через которую авторизовался пользователь
		//$user['identity'] - уникальная строка определяющая конкретного пользователя соц. сети
		//$user['first_name'] - имя пользователя
		//$user['last_name'] - фамилия пользователя
		//print_r($user);exit;
		if(!$user['uid']){throw new HttpException(500,'Не найден UID');}
		$openid = Ulogin::findOne(['email'=>$user['uid'], 'network'=>$user['network']]);
		print_r($openid);
		if(!isset($openid))
		{
			$oi = new Ulogin();
			$oi->user = \Yii::$app->user->id;
			$oi->uid = $user['uid'];
			$oi->network = $user['network'];
			$oi->identity = $user['identity'];
			$oi->email = $user['email'];
			$oi->save();
		}else {
			throw new HttpException(500, 'Этот аккаунт уже связан с другим пользователем');
		}
	}

	public function actionUloginList(){
		return Json::encode(Ulogin::findAll(['user'=>\Yii::$app->user->id]));
	}

}
