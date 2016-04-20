<?php
return [
	'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
	'language'=>'ru_RU',
	'name' => 'Абрикосизм',
	'components' => [
		'cache' => [
			'class' => 'yii\caching\FileCache',
		],
		'authManager' => [
			'class' => 'yii\rbac\DbManager',
		],
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => [
				'<controller:\w+>/<view:\w+>/<id:\d+>' => '<controller>/<view>',
				'<controller:\w+>/<id:\d+>' => '<controller>/view',
				'ykt'=>'/site/30sec'
			],
		],
		'assetManager'=>[
			'forceCopy'=>true,
		],

	],

	'modules' => [
		'debug'=>[
			'class' => 'yii\debug\Module',
			'allowedIPs' => ['91.185.237.153', '127.0.0.1']
		],
		'gii'=>[
			'class' => 'yii\gii\Module',
			'allowedIPs' => ['127.0.0.1', '::1', '91.185.237.153'] // adjust this to your needs
		],
	],
	'bootstrap'=>['debug', 'gii'],

];
