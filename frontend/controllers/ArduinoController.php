<?php
/**
 * Created by PhpStorm.
 * User: abrikos
 * Date: 30.04.16
 * Time: 16:13
 */

namespace frontend\controllers;


use Yii;
use yii\helpers\Json;
use yii\web\Controller;

class ArduinoController extends Controller
{
	public function actionPolivalka($water)
	{
		$command = Yii::$app->db->createCommand('select * from flood');
		$ret = $command->queryOne();
		$power = 0;
		$delta = 0;
		if($water){
			$delta = $ret['last'] + $ret['interval']*3600 - time();
			if( $delta<=0  ){
				$power  = 1;
				$command = Yii::$app->db->createCommand('update flood set date=now(), last='.time());
				$ret = $command->execute();
			}
		}else{
			$mailer = Yii::$app->mailer->compose()
				->setFrom(['abrikoscron@gmail.ru' => \Yii::$app->name . '. Робот'])
				->setTo('abrikoz@gmail.com')
				->setSubject('Закончилась вода')
				->setTextBody('Воду надо долить');
			//if(!$mailer->send()){
			//	throw new HttpException(500,'E-mail не отправлен. Обратитесь в тех-поддержку разделе "Помошь"');
			//}
		}
		//error_log(date('Ymd H:i:s') ."\t$power\n",3,'arduino.log');
		return Json::encode(['duration'=>$ret['duration'],'power'=>$power, 'debug'=>1, 'delta'=>$delta]);
		/*
		//error_log(date('Ymdhis'). " " . $water. "\n",3,"arduino.log");
		$ret['flood'] = rand(0,10);
		$ret['interval'] = 24 * 3600 * 1000;
		$ret['interval'] = 60;
		$ret['duration'] = 2;
		return Json::encode($ret);
		*/
	}

	public function actionIndex()
	{
		return $this->render('index');
	}
}