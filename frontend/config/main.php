<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'MetaTags'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
	    'MetaTags' => [
		    'class' => 'app\components\MetaTags',
	    ],
	    'i18n' => [
		    'translations' => [
			    'calendar*' => [
				    'class' => 'yii\i18n\PhpMessageSource',
				    //'basePath' => '@app/messages',
				    //'sourceLanguage' => 'en-US',
				    'fileMap' => [
					    'calendar' => 'calendar.php',
					    'app/error' => 'error.php',
				    ],
			    ],
		    ],
	    ],
    ],
	'modules'=>[
		'miner' => [
			'class' => 'app\modules\miner\Module',
		],
		'cows' => [
			'class' => 'app\modules\cows\Module',
		],
		'calendar' => [
			'class' => 'app\modules\calendar\Calendar',
		],
	],

	'params' => $params,
];
