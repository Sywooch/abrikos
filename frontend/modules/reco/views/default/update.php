<?php
/**
 * Created by PhpStorm.
 * User: abrikos
 * Date: 05.05.16
 * Time: 11:08
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\jui\DatePicker;

\frontend\modules\reco\ModuleAsset::register($this);
$this->title = 'Редактировать игру  "' . $model->name . '"';
$this->params['breadcrumbs'][] = ['label'=>$this->context->module->name,'url'=>'/' . $this->context->module->id];
$this->params['breadcrumbs'][] = ['label' => 'Список', 'url' => ['/'.$this->context->module->id.'/list']];
$this->params['breadcrumbs'][] = ['label' => 'Просмотр #' . $model->id, 'url' => $model->link];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-lg-6">
		<div id="tour-container"></div>
		<div id="images-removed" class="hidden"></div>
	</div>
	<div class="col-lg-6">
		<div class="hidden" id="quest-data" data-id="<?=$model->id?>"></div>
		<?php DatePicker::widget([
			'model' => $model,
			'attribute' => 'date',
			//'language' => 'ru',
			//'dateFormat' => 'yyyy-MM-dd',
		]);?>
		<?php $form = ActiveForm::begin([
			'id'=>$this->context->module->id. '-quest-form',
			'options' => [
				'data'=>['model'=>'quest','id'=>$model->id],
				'onsubmit'=>'return questUpdate(this,'.$model->id.')',
				'onchange'=>'return questUpdate(this,'.$model->id.')',
			],
			'validateOnChange'=>false,
			'validateOnSubmit'=>false,
			'enableAjaxValidation' => false,
			'enableClientValidation' => false,

		]); ?>
		<?= $form->field($model, 'name')->textInput(['placeholder'=>'Введите название игры'])?>
		<?= $form->field($model, 'email')->textInput(['placeholder'=>'Введите Ваш адрес'])->hint('Адрес используется для восстановления доступа и информирования об ответах') ?>
		<?= $form->field($model, 'sendmail')->checkbox()?>
		<?= $form->field($model, 'published')->checkbox()?>
		<?php ActiveForm::end(); ?>
		<img src="<?=$model->image?>" alt="Quest image" id="quest-image"/>
		<h3>Список всех заданий <small>сортировка перетаскиванием</small></h3>
		<div id="tours-list-container" class="list-group"></div>
		<!--form onsubmit="return formSearchByWord(this)" class="form-inline">
			<div class="form-group">
				<label for="input-word-search">Подобрать картинки под ответ</label>
				<input class="form-control" id="input-word-search" placeholder="Введите слово или фразу" name="word">
			</div>
			<button type="submit" class="btn btn-default">Искать</button>
			<p class="help-block">Оставьте пустым для случайного поиска</p>
		</form>
		<div id="search-result"></div-->
	</div>
</div>

<script>
	<?php
	foreach ($model->tours as $i=>$tour){
		$ret[] =$tour->formatter;
	}
	?>
	var TOURS = <?=\yii\helpers\Json::encode($ret)?>;
</script>

<script type="text/x-tmpl" id="tmpl-search-result">
	<?=$this->render('update/tmpl-search-result')?>
</script>

<script type="text/x-tmpl" id="tmpl-search-image">
	<?=$this->render('update/tmpl-search-image')?>
</script>

<script type="text/x-tmpl" id="tmpl-choosen-image">
	<?=$this->render('update/tmpl-choosen-image')?>
</script>

<script type="text/x-tmpl" id="tmpl-add-media">
		<?=$this->render('update/tmpl-add-media')?>
</script>
<script type="text/x-tmpl" id="tmpl-tour-form">
		<?=$this->render('update/tmpl-tour-form')?>
</script>
<script type="text/x-tmpl" id="tmpl-tour-list-item">
		<?=$this->render('update/tmpl-tour-list-item')?>
</script>