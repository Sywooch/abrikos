<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
	<?php
	NavBar::begin([
		'brandLabel' => Yii::$app->name,
		'brandUrl' => Yii::$app->homeUrl,
		'options' => [
			'class' => 'navbar-inverse navbar-fixed-top',
		],
	]);
	$menuItems = [
		['label'=>'Игры', 'items'=>
			[
				['label' => 'Сапер', 'url' => ['/miner']],
				['label' => 'Бики и коровы', 'url' => ['/cows']],
			]
		],

	];
	if (Yii::$app->user->isGuest) {
		$menuItems[] = ['label' => 'Регистрация', 'url' => ['/site/signup']];
		$menuItems[] = ['label' => 'Вход', 'url' => ['/site/login']];
	} else {
		$menuItems[] = ['label' => 'AddPic', 'url' => ['/site/add-pic']];
		$menuItems[] = '<li>'
			. Html::beginForm(['/site/logout'], 'post')
			. Html::submitButton(
				'Выход (' . Yii::$app->user->identity->username . ')',
				['class' => 'btn btn-link']
			)
			. Html::endForm()
			. '</li>';
	}
	echo Nav::widget([
		'options' => ['class' => 'navbar-nav navbar-right'],
		'items' => $menuItems,
	]);
	NavBar::end();
	?>

	<div class="container">
		<?= Breadcrumbs::widget([
			'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
		]) ?>
		<?= Alert::widget() ?>
		<?=$this->render('google-ads')?>
		<?= $content ?>
	</div>
	<!--div style="text-align: center">
		<a href="http://zdravoeshka.ru/" target="_blank"><img src="/images/zdravoeshka.png" alt="Здраавоешка" class="img-responsive" style="margin: auto"/></a>
	</div-->

</div>

<footer class="footer">
	<div class="container">
		<p class="pull-left">&copy;Абрикосизм <?= date('Y') ?></p>
		<p class="pull-right"><p class="pull-right"><?=$this->render('statistics')?></p></p>
	</div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
