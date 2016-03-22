<?php
namespace app\modules\cows;

use yii\web\View;
use yii\web\AssetBundle;

class CowsAsset extends AssetBundle
{
	public $sourcePath = '@app/modules/cows/assets/';
	public $baseUrl = '@web';
	public $css = [
		'css/cows.css',
	];
	public $js = [
		'js/cows.js',
	];
	//public $jsOptions = ['position' => View::POS_END];

	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
	];
}