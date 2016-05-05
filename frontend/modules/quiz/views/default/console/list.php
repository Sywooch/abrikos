<?php
use yii\bootstrap\Html;
use yii\grid\GridView;

$this->title = 'Мои викторины';
$this->params['breadcrumbs'][] = $this->title;

?>
<h1><?= Html::encode($this->title) ?></h1>
<?=\yii\bootstrap\Html::a('Новая викторина','/quiz/create',['data'=>['method'=>'post'], 'class'=>['btn','btn-primary']])?>
<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'columns' => [
		'id',
		[
			'label'=>'Викторина',
			'format' => 'raw',
			'value'=>function ($data) {
				return Html::a($data->name,'/quiz/update/'.$data->id);
			},
		],
		'date',
		[
			'class' => 'yii\grid\ActionColumn',
			'template' => '{view} {delete} ',
		],
	],

]); ?>