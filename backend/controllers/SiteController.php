<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class SiteController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['login', 'error', 'ulogin'],
						'allow' => true,
					],
					[
						'actions' => ['logout', 'index'],
						'allow' => true,
						'roles' => ['@'],
					],
					[
						'allow' => true,
						'roles' => ['admin'],
					]
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'logout' => ['post'],
				],
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
		];
	}

	public function actionFlood()
	{
		$params = [];
		foreach (Yii::$app->request->post() as $key=>$val){
			$sql[] = '`' . $key . '`=:'.$key;
			$params[':'.$key] =$val;
		}
		$sets = implode(',',$sql);
		$command = Yii::$app->db->createCommand('update flood set '. $sets,$params);
		//print $command->rawSql; exit;
		$command->execute();
	}

	public function actionIndex()
	{
		return $this->render('index');
	}

	public function actionLogin()
	{
		if (!\Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->goBack();
		} else {
			return $this->render('@app/../frontend/views/site/login', [
				'model' => $model,
				'show' => 1
			]);
		}
	}

	public function actionUlogin(){
		//$r =10;
		$r = \frontend\controllers\SiteController::actionUlogin();
		return  $r;
	}


	public function actionLogout()
	{
		Yii::$app->user->logout();

		return $this->goHome();
	}
}
