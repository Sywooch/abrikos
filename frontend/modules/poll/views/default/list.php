<?php
use yii\bootstrap\Html;
use yii\grid\GridView;

$this->title = 'Мои опросы';
$this->params['breadcrumbs'][] = $this->title;

?>
<h1><?= Html::encode($this->title) ?></h1>
<?=\yii\bootstrap\Html::a('Новый опрос','/poll/create',['data'=>['method'=>'post'], 'class'=>['btn','btn-primary']])?>
<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'columns' => [
		'id',
		'text',
		'date',
		[
			'class' => 'yii\grid\ActionColumn',
			'template' => '{view} {delete} ',
		],
	],
	'rowOptions' => function ($model, $key, $index, $grid) {
		return ['id' => "row-" . $model['id'], 'onclick' => 'document.location.href = "/poll/update/"+this.getAttribute("data-key");', 'style'=>'cursor:pointer'];
	},

]); ?>