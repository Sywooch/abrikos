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
<?=$this->render('@app/views/layouts/facebook-init')?>
<div class="wrap">
	<nav id="top-nav">
		<a href="/"><?=Yii::$app->name?></a> /
		<a href="/quiz/">Викторины</a> /
		<a href="/quiz/create" data-method="post">Создать свою </a>
		<div id="social-share">
			<?=$this->render('@app/views/layouts/facebook-share')?>
		</div>

	</nav>
	<div class="container">
		<?= Alert::widget() ?>
		<?= $content ?>
		<?=$this->render('@app/views/layouts/facebook-comments')?>
	</div>
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
