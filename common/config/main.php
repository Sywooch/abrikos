<?php
return [
	'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
	'language'=>'ru_RU',
	'name' => 'Web-сервисы',
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
				'<module:cows>/<action:\w+(-\w+)*>' => '<module>/default/<action>',
				'<module:miner>/<action:\w+(-\w+)*>' => '<module>/default/<action>',
				'<module:calendar>/<action:\w+(-\w+)*>' => '<module>/default/<action>',
				'<module:seawar>/<action:\w+(-\w+)*>' => '<module>/default/<action>',
				'<module:poll>/<action:.*>/<id:\d*>' => '<module>/default/<action>',
				'<module:poll>/<id:\d*>' => '<module>/default/view',
				'<module:poll>/<action:.*>' => '<module>/default/<action>',
				'<module:quiz>/<action:.*>/<id:\d*>' => '<module>/default/<action>',
				'<module:quiz>/<id:\d*>' => '<module>/default/view',
				'<module:quiz>/<action:.*>' => '<module>/default/<action>',

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
