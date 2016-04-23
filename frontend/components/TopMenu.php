<?php
/**
 * Created by PhpStorm.
 * User: abrikos
 * Date: 11.04.16
 * Time: 18:21
 */

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
				]
			],
			['label'=>'Игры', 'items'=>
				[
					['label' => 'Сапер', 'url' => ['/miner']],
					['label' => 'Быки и коровы', 'url' => ['/cows']],
				]
			],

		];
		if (Yii::$app->user->isGuest) {
			$items[] = ['label' => 'Регистрация', 'url' => ['/site/signup']];
			$items[] = ['label' => 'Вход', 'url' => "/site/login"];
		} else {
			$items[] = ['label' => 'Кабинет', 'url' => ['/user/cabinet']];
			$items[] = [
				'label' => 'Выход (' . Yii::$app->user->identity->username . ')',
				'url' => ['/site/logout'],
				'linkOptions' => ['data-method' => 'post']
			];
		}
		self::$items = $items;
		parent::init();
	}
}