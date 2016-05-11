<?php
use yii\bootstrap\Html;
use yii\grid\GridView;

$this->title = $this->context->module->name ;
$this->params['breadcrumbs'][] = $this->title;
\frontend\modules\reco\ModuleAsset::register($this);
?>
<h1><?= Html::encode($this->title) ?></h1>
<div class="row quests-container">
<?php
$quests = \frontend\modules\reco\models\Quest::find()->where('published>0')->orderBy('date desc')->all();
foreach ($quests as $quest):?>
	<a class="col-lg-3 quest-wrap" href="<?=$quest->link?>">
		<div class="thumbnail image-wrap">
		<img src="<?=$quest->image?>" />
		</div>
		<?=$quest->name?>
	</a>
<?php endforeach?>
</div>
<?=\yii\bootstrap\Html::a('Создать свои 4 картинки','/'.$this->context->module->id.'/create',['data'=>['method'=>'post'], 'class'=>['btn','btn-primary']])?>