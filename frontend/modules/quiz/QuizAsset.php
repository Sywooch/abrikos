<?php
namespace frontend\modules\quiz;

use yii\web\View;
use yii\web\AssetBundle;

class QuizAsset extends AssetBundle
{
	public $sourcePath = '@app/modules/quiz/assets';
	public $baseUrl = '@web';
	public $css = [
		'css/quiz.css',
	];
	public $js = [
		'js/quiz.js',
		//'js/tmpl.min.js',
	];
	public $jsOptions = ['position' => View::POS_HEAD];

	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
	];
}