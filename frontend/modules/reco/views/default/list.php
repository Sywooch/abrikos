<?php
use yii\bootstrap\Html;
use yii\grid\GridView;

$this->title = 'Список';
$this->params['breadcrumbs'][] = ['label'=>$this->context->module->name,'url'=>'/' . $this->context->module->id];
$this->params['breadcrumbs'][] = $this->title;
\frontend\modules\reco\ModuleAsset::register($this);
?>
<h1><?= Html::encode($this->title) ?></h1>
<?=\yii\bootstrap\Html::a('Новая игра','/'.$this->context->module->id.'/create',['data'=>['method'=>'post'], 'class'=>['btn','btn-primary']])?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'rowOptions'=>function ($model, $key, $index, $grid){
		$class=$index%2?'odd':'even';
		return [
			'id'=>'grid-row-' . $key,
			'key'=>$key,
			'index'=>$index,
			'class'=>$class
		];
	},
	'columns' => [
		'id',
		[
			'label'=>'Картинка',
			'format' => 'raw',
			'value'=>function ($data) {
				return Html::a(Html::img($data->image, ['class'=>'list-image']),'/'.$this->context->module->id.'/update/'.$data->id);
			},
		],
		'tours_count',
		[
			'class' => 'yii\grid\ActionColumn',
			'template' => '{view} {delete} ',
			'buttons'=>[
				'delete' => function($url, $model){
					return Html::a('','javascript:void(0)',['class'=>'glyphicon glyphicon-trash', 'onclick'=>'questDelete('.$model->id.')']);
				}
			]
		],
	],

]); ?>
<script>
	function questDelete(id) {
		$.post('/<?=$this->context->module->id?>/quest-delete/'+id,null,function () {
			$('#grid-row-'+id).fadeOut();
			console.log($(this).parent());
		})
	}
</script>
