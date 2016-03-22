<?php
namespace app\modules\miner;

use yii\web\View;
use yii\web\AssetBundle;

class MinerAsset extends AssetBundle
{
	public $sourcePath = '@app/modules/miner/assets';
	public $baseUrl = '@web';
	public $css = [
		'css/miner.css',
		'images/bomb.svg',
	];
	public $js = [
		'js/miner.js',
		'js/miner-svg.js',
		'js/snap.svg-min.js',

	];
	//public $jsOptions = ['position' => View::POS_END];

	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
	];
}