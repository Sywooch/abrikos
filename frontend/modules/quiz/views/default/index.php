<?php
use yii\helpers\Html;

$this->title = 'Викторины. Начало';
$this->params['breadcrumbs'][] = $this->title;
?>
	<h1><?= Html::encode($this->title) ?></h1>
<?=\yii\bootstrap\Html::a('Создать викторину', '/quiz/create',['class'=>'btn btn-primary', 'data'=>['method'=>'post']])?>