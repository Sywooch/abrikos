<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\components\TopMenu;
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
<?=$this->render('@app/views/layouts/facebook-init')?>
<div class="wrap">
	<?php
	NavBar::begin([
		'brandLabel' => Yii::$app->name,
		'brandUrl' => Yii::$app->homeUrl,
		'options' => [
			'class' => 'navbar-inverse navbar-fixed-top',
		],
	]);
	echo Nav::widget([
		'options' => ['class' => 'navbar-nav navbar-right'],
		'items' => TopMenu::addItems([
			['label'=>'Викторины','items'=>[
				['label'=>'Начало', 'url'=>'/quiz'],
				['label'=>'Мои викторины', 'url'=>'/quiz/list'],
				['label'=>'Статистика', 'url'=>'/quiz/stat'],
				['label'=>'Создать свою викторину', 'url'=>'/quiz/create','linkOptions'=>['data'=>['method'=>'post']]],
				['label'=>'Восстановление доступа', 'url'=>'/quiz/restore'],
			]]
		]),
	]);
	NavBar::end();
	?>

	<div class="container">
		<?= Breadcrumbs::widget([
			'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
		]) ?>
		<?= Alert::widget() ?>
		<?= $content ?>
		<?=$this->render('@app/views/layouts/facebook-comments')?>
	</div>

	<?php
	NavBar::begin([
		'id'=>'navbar-bottom',
		'options' => [
			'class' => 'navbar navbar-default navbar-fixed-bottom  collapse',
		],
	]);
	echo '<div id="console-log" class="alert alert-danger"></div>';
	NavBar::end();
	?>
</div>

<footer class="footer">
	<div class="container">
		<p class="pull-left">&copy; <?= Yii::$app->name . ' ' . date('Y') ?></p>
		<p class="pull-right"><p class="pull-right"><?=$this->render('@app/views/layouts/statistics')?></p></p>
	</div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
