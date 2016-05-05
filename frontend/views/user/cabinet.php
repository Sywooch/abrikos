<?php
use yii\bootstrap\Tabs;

//Yii::$app->user->identity->last_name = 'zzzz';
//Yii::$app->user->identity->save();

$this->title = 'Кабинет пользователя ' ;
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?=$this->title?></h1>
<?php
echo Tabs::widget([
	'items' => [
		[
			'label' => 'Профиль',
			'content' => $this->render('card'),
			'active' => true
		],
		[
			'label' => 'Пароль',
			'content' => $this->render('password'),
		],

        /*
		[
	        'label' => 'Dropdown',
	        'items' => [
		        [
			        'label' => 'DropdownA',
			        'content' => 'DropdownA, Anim pariatur cliche...',
		        ],
		        [
			        'label' => 'DropdownB',
			        'content' => 'DropdownB, Anim pariatur cliche...',
		        ],
	        ],
        ],
        */
    ],
]);
?>
