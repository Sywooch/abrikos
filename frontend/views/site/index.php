<?php
use yii\bootstrap\Html;

$this->title = Yii::$app->name;
?>

<div id="site-index-wrapper">
	<div class="index-block bg-color-3">
		<img src="/images/logo.png" />
		<h1><?= Html::encode($this->title) ?></h1>
	</div>
	<div class="index-block bg-color-2">
		<h2>Викторины</h2>
		<?=$this->render('@app/modules/quiz/views/default/about')?>
	</div>
	
	<div class="index-block bg-color-1">
		<h2>Опросы</h2>
		<?=$this->render('@app/modules/poll/views/default/about')?>
	</div>
	
</div>