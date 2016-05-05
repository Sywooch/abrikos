<?php
/**
 * Created by PhpStorm.
 * User: abrikos
 * Date: 01.05.16
 * Time: 22:36
 */

namespace frontend\assets;


use yii\web\AssetBundle;
use yii\web\View;

class ToolsAsset extends AssetBundle
{
	public $sourcePath = '@app/assets';
	public $baseUrl = '@web';
	public $css = [
		'css/tools.css',
	];
	public $jsOptions = ['position'=>View::POS_HEAD];
	public $js = [
		'js/tools.js'
	];
	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
	];
}