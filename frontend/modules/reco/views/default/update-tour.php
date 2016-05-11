<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<input id="model-id" value="<?=$model->id?>" type="hidden"/>
<div class="row">
	<div class="col-lg-6">
		<div id="images-table" class="row"></div>
		<div class="help-block">Клик по картинке для загрузки другого изображения.</div>
		<button onclick="imageChoosenDownload()" class="btn btn-primary collapse" id="button-choosen-download">Сохранить картинки</button>
		<h3>Параметры <small>сохраняются автоматически</small></h3>
		<?php $form = ActiveForm::begin([
			'id'=>$this->context->module->id. '-tour-form',
			'options' => [
				'data'=>['model'=>'tour'],
				'onsubmit'=>'return modelUpdate(this)',
				'onchange'=>'return modelUpdate(this)',
				],
			'validateOnChange'=>false,
			'validateOnSubmit'=>false,
			'enableAjaxValidation' => false,
			'enableClientValidation' => false,
		]); ?>
		<?= $form->field($model, 'email')->textInput(['placeholder'=>'Введите Ваш адрес'])->hint('Адрес используется для восстановления доступа и информирования об ответах') ?>
		<?= $form->field($model, 'sendmail')->checkbox()?>
		<?= $form->field($model, 'answer')->textInput(['placeholder'=>'Введите слово']) ?>
		<?= $form->field($model, 'letters_count')->radioList([5=>5,6=>6,7=>7,8=>8,9=>9,10=>10])->hint('Каждое сохранение генерирует новые дополнительные буквы') ?>
		<div id="form-letters"></div>
		<div class="hidden"><?= $form->field($model, 'shuffle')->textArea() ?></div>
		<a href="<?=$model->link?>" class="btn btn-info" target="_blank">Просмотр</a>
		<div id="form-image-inputs"></div>
		<?php ActiveForm::end(); ?>
		<?php $form = ActiveForm::begin(['id'=>$this->context->module->id. '-publicate-form', 'options' => [
			'onsubmit'=>'return publicate()',
			'onchange'=>'return publicate()',
		], 	'validateOnChange'=>false,
			'validateOnSubmit'=>false,
			'enableAjaxValidation' => false,
			'enableClientValidation' => false,
		]); ?>
			<?= $form->field($model, 'enabled')->radioList(['Не опубликовано','Опубликовано'])?>
		<?php ActiveForm::end()?>

		<div id="images-removed" class="hidden"></div>
	</div>
	<div class="col-lg-6">
		<form onsubmit="return formSearchByWord(this)" class="form-inline">
			<div class="form-group">
				<label for="input-word-search">Подобрать картинки под образ</label>
				<input class="form-control" id="input-word-search" placeholder="Введите слово или фразу" name="word">
			</div>
			<button type="submit" class="btn btn-default">Искать</button>
			<p class="help-block">Оставьте пустым для случайного поиска</p>
		</form>
		<div id="search-result"></div>
	</div>
</div>

<script type="text/x-tmpl" id="tmpl-search-result">
	<?=$this->render('tmpl-search-result')?>
</script>

<script type="text/x-tmpl" id="tmpl-search-image">
	<?=$this->render('tmpl-search-image')?>
</script>

<script type="text/x-tmpl" id="tmpl-choosen-image">
	<?=$this->render('tmpl-choosen-image')?>
</script>

<script type="text/x-tmpl" id="tmpl-add-media">
		<?=$this->render('tmpl-add-media')?>
</script>
