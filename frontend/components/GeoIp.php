<?php
namespace app\components;


use yii\base\Component;
use yii\helpers\Json;

class GeoIp extends Component
{

	static public function get($address)
	{
		return file_get_contents('http://geoip.nekudo.com/api/'.$address.'/ru/full');
	}

	static public function getArray($address)
	{
		return Json::decode(self::get($address));
	}
}