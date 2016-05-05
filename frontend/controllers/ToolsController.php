<?php
/**
 * Created by PhpStorm.
 * User: abrikos
 * Date: 01.05.16
 * Time: 22:33
 */

namespace frontend\controllers;

use yii\helpers\Json;
use yii\web\Controller;

class ToolsController extends Controller
{
	public function actionIndex()
	{
		return $this->render('index');
	}

	public function actionJson($type)
	{
		//print '<pre>';print_r($_GET);exit;
		$data = \Yii::$app->request->get('data');
		$return = [];
		switch($type){
			case 'geoip':
				 $ipdata =  \app\components\GeoIp::getArray($data['address']);
				 $return['Запрос'] = $data['address'];
				 $return['IP'] = $ipdata['traits']['ip_address'];
				 $return['Координаты'] = $ipdata['location']['latitude'] . ' ' . $ipdata['location']['longitude'];
				 $return['Местоположение'] = $ipdata['location']['time_zone'];
				 $return['Страна'] = $ipdata['country']['names']['ru'];
				 $return['Регион'] = $ipdata['subdivisions'][0]['names']['ru'];
				 $return['Город'] = $ipdata['city']['names']['ru'];
				 $return['Индекс'] = $ipdata['postal']['code'];
				break;
			case 'nslookup':
				$return['Запрос'] = $data['address'];
				$return['IP'] = gethostbyname($data['address']);
				break;
			case 'whois':
				$return['Запрос'] = $data['address'];
				$return['WHOIS'] = '<pre>' .  `whois {$data['address']}` . '</pre>';
				break;
			case 'ping':
				$return['Запрос'] = $data['address'];
				$return['Ping'] = '<pre>' .  `ping -c 4 {$data['address']}` . '</pre>';
				break;

		}
		return Json::encode($return);
	}

}