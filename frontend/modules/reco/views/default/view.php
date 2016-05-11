<?php
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label'=>$this->context->module->name,'url'=>'/' . $this->context->module->id];
$this->params['breadcrumbs'][] = ['label' => 'Список', 'url' => ['/'.$this->context->module->id.'/list']];
$this->params['breadcrumbs'][] = $this->title;

Yii::$app->view->registerMetaTag(['property'=>'og:title', 'content'=>$this->title, 'id'=>'og-title'], 'og-title');
Yii::$app->view->registerMetaTag(['property'=>'og:description', 'content'=>'Угадай слово по 4-м картинкам'], 'og-desc');

Yii::$app->view->registerMetaTag(['property' => 'og:image', 'content' => 'http://'.$_SERVER['SERVER_NAME'].$model->image],'og-image');
Yii::$app->view->registerMetaTag(['property' => 'og:image:secure_url', 'content' => 'http://'.$_SERVER['SERVER_NAME'].$model->image],'og-imagesec');
Yii::$app->view->registerMetaTag(['property' => 'og:image:width', 'content' => 200],'og-image-width');
Yii::$app->view->registerMetaTag(['property' => 'og:image:height', 'content' => 200],'og-image-height');
Yii::$app->view->registerLinkTag(['rel' => 'image_src', 'href' => 'http://'.$_SERVER['SERVER_NAME'].$model->image],'og-image-rel');


use frontend\modules\reco\models\Tour;

\frontend\modules\reco\ModuleAsset::register($this);
?>
<h1><?=$this->title?></h1>
<div id="view-container"></div>

<script type="text/x-tmpl" id="tmpl-tour-view">
		<?=$this->render('view/tmpl-tour-view')?>
</script>
<script type="text/x-tmpl" id="tmpl-tour-done">
		<?=$this->render('view/tmpl-tour-done')?>
</script>

<input type="hidden" id="quest-id" value="<?=$model->id?>" />
<input type="hidden" id="start-tour-id" value="<?=$tour?>" />

<?php if(Yii::$app->user->can('moderator') || $model->session == Yii::$app->session[$this->context->module->id]):?>
<a href="/<?=$this->context->module->id?>/update/<?=$model->id?>"  class="btn btn-xs btn-warning">Редактировать</a>
<?php endif ?>
<button class="btn btn-xs btn-danger" onclick="helpShow('abuse', $(this).data('var'))" data-var="" id="btn-abuse">Пожаловаться</button>

<hr/>

<div class="btn btn-primary">
<a href="/<?=$this->context->module->id?>/create" data-method="post" class="btn btn-primary">Попробуйте создать свою игру!</a>
</div>