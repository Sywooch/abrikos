<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
\frontend\modules\quiz\QuizConsoleAsset::register($this);
$this->title = 'Редактирование quiz #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Мои викторины', 'url' => ['list']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?> <small>все изменения сохраняются автоматически</small></h1>
<div class="row">
	<div class="col-md-6">
		<div class="quiz-update">

			<?php yii\widgets\Pjax::begin() ?>
			<div class="quiz-form">

				<?php $form = ActiveForm::begin(['id'=>'quiz-form', 'options' => ['data-pjax' => true, 'onchange'=>'if (typeof formSubmit == "function") { formSubmit(this);}']]); ?>
				<div class="collapse"><?= $form->field($model, 'id')->textInput() ?></div>
				<?= $form->field($model, 'name')->textInput(['placeholder'=>'Введите название викторины']) ?>
				<?= $form->field($model, 'description')->textarea(['rows' => 6, 'placeholder'=>'Введите описание викторины']) ?>

				<?= $form->field($model, 'enabled')->checkbox()->hint('разрешить посетителям участвовать в викторине') ?>

				<?php ActiveForm::end(); ?>

			</div>
			<?php \yii\widgets\Pjax::end(); ?>
		</div>
		<!--form enctype="multipart/form-data" action="/quiz/upload/16?type=quest" method="post" onchange="$(this).submit()">
			<input name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>">
			<input type="file" name="image">
		</form-->
		<h3>Вопросы</h3>
		<div id="quest-list"></div>
	</div>
	<div id="quest-right" class="col-md-6">Опции</div>
	</div>


<?=\yii\bootstrap\Html::a('Добавить вопрос', 'javascript:questAdd(' . $model->id .')' ,['class'=>'btn btn-primary'])?>



<script type="text/x-tmpl" id="tmpl-quest">
<?=$this->render('tmpl-quest')?>
</script>

<script type="text/x-tmpl" id="tmpl-answer">
	<?=$this->render('tmpl-answer')?>
</script>

<script type="text/x-tmpl" id="tmpl-add-media">
	<?=$this->render('tmpl-add-media')?>
</script>
<div class="collapse"><?= yii\jui\DatePicker::widget(['name' => 'attributeName']) ?></div>