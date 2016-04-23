<?php
namespace frontend\modules\quiz;

use yii\web\View;
use yii\web\AssetBundle;

class QuizConsoleAsset extends AssetBundle
{
	public $sourcePath = '@app/modules/quiz/assets';
	public $baseUrl = '@web';
	public $css = [
		'css/quiz-console.css',
	];
	public $js = [
		'js/quiz-console.js',
		'js/tmpl.min.js',
		//'js/jquery.loadTemplate-1.4.4.min.js'
	];
	public $jsOptions = ['position' => View::POS_HEAD];

	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
	];
}