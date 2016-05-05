<?php
namespace app\components;


use Yii;
use yii\base\Component;

class TopMenu extends Component
{
	static public $items ;
	public function init()
	{
		$items = [
			['label'=>'Сервисы', 'items'=>
				[
					['label' => 'Опросы', 'url' => ['/poll']],
					['label' => 'Викторины', 'url' => ['/quiz']],
					['label' => 'Стабильный календарь', 'url' => ['/calendar']],
					['label' => 'IP инструменты', 'url' => ['/tools']],
				]
			],
			['label'=>'Игры', 'items'=>
				[
					['label' => 'Сапер', 'url' => ['/miner']],
					['label' => 'Быки и коровы', 'url' => ['/cows']],
				]
			],
			['label'=>'Помошь','url'=>'/site/contact']

		];
		if (Yii::$app->user->isGuest) {
			$items[] = ['label' => 'Регистрация', 'url' => ['/site/signup']];
			$items[] = ['label' => 'Вход', 'url' => "/site/login"];
		} else {
			$items[] = ['label' => 'Кабинет', 'url' => ['/user/cabinet']];
			$items[] = '<li>'. Yii::$app->user->identity->card . '</li>';
			$items[] = [
				'label' => 'Выход',
				'url' => ['/site/logout'],
				'linkOptions' => ['data-method' => 'post']
			];
		}
		self::$items = $items;
		parent::init();
	}

	static public function addItems($new)
	{
		self::$items = array_merge($new,self::$items);
	}
}