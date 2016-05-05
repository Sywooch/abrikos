<?php
use yii\helpers\Html;

$this->title = 'Викторины. Начало';
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="quiz-index-wrap">
	<h1><?= Html::encode($this->title) ?></h1>
	<?=$this->render('about')?>
</div>
