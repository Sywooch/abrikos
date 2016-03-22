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
						'roles' => ['admin'],
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

	public function actionPasswordChange(){
		$password = \Yii::$app->request->post('password');
		$user = User::findOne(\Yii::$app->user->id);
		if(!$user->validatePassword($password['old'])){
			return 'Не верный старый пароль';
		}
		if(!$password['new']){
			return 'Новый пароль не может быть пустым';
		}

		if($password['new']!=$password['new2']){
			return 'Новый пароль и подтверждение должны совпадать';
		}
		$user->setPassword($password['new']);
		$user->save();
		return 1;
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
