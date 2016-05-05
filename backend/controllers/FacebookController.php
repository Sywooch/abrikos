<?php
/**
 * Created by PhpStorm.
 * User: abrikos
 * Date: 29.04.16
 * Time: 10:43
 */

namespace backend\controllers;


use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class FacebookController extends Controller
{
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow' => true,
						'roles' => ['@'],
					],
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


	public function actionAccessToken()
	{
		$url = 'https://graph.facebook.com/oauth/access_token?client_id='
			. Yii::$app->request->post('app_id')
			.'&client_secret='.Yii::$app->request->post('app_secret')
			.'&grant_type=client_credentials';
		return file_get_contents($url);
	}

	public function actionGraph()
	{
		$url = 'http://graph.facebook.com/v2.6/'
			. Yii::$app->request->post('access-token')
			.'/' .  Yii::$app->request->post('request');
		//return $url;
		return file_get_contents($url);
	}
}