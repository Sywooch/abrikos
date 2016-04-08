<?php
namespace app\modules\calendar;

use yii\web\View;
use yii\web\AssetBundle;

class CalendarAsset extends AssetBundle
{
	public $sourcePath = '@app/modules/calendar/assets';
	public $baseUrl = '@web';
	public $css = [
		'css/calendar.css',
	];
	public $js = [
		'js/calendar.js',
	];
	//public $jsOptions = ['position' => View::POS_END];

	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
	];
}