<?php
namespace app\modules\seawar;

use yii\web\View;
use yii\web\AssetBundle;

class SeawarAsset extends AssetBundle
{
	public $sourcePath = '@app/modules/seawar/assets';
	public $baseUrl = '@web';
	public $css = [
		'css/seawar.css',
	];
	public $js = [
		'js/seawar.js',
	];
	//public $jsOptions = ['position' => View::POS_END];

	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
	];
}