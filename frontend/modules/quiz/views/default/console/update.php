<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
\frontend\modules\quiz\QuizConsoleAsset::register($this);
$this->title = 'Редактирование quiz #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Мои викторины', 'url' => ['list']];
$this->params['breadcrumbs'][] = ['label' => 'Просмотр #' . $model->id, 'url' => $model->link];
$this->params['breadcrumbs'][] = $this->title;
?>
<div  id="update-container">
	<h1><?= Html::encode($this->title) ?> <small>все изменения сохраняются автоматически</small></h1>
	<div class="row">
		<div class="col-md-6">
			<div class="quiz-update">
				<div class="quiz-form">
					<?php $form = ActiveForm::begin(['id'=>'quiz-form', 'options' => ['onchange'=>'return quizUpdate(this)']]); ?>
					<div class="collapse"><?= $form->field($model, 'id')->textInput() ?></div>
					<?= $form->field($model, 'name')->textInput(['placeholder'=>'Введите название викторины']) ?>
					<?= $form->field($model, 'description')->textarea(['rows' => 6, 'placeholder'=>'Введите описание викторины']) ?>
					<?php ActiveForm::end(); ?>

				</div>
			</div>
			<h3>Вопросы <?=\yii\bootstrap\Html::a('Добавить вопрос', 'javascript:questAdd(' . $model->id .')' ,['class'=>'btn btn-primary btn-xs'])?></h3>
			<div id="quest-list"></div>
		</div>
		<div class="col-md-6">
			<div id="quest-right" >
				<?=$this->render('options',['model'=>$model])?>
			</div>

		</div>
	</div>


	<?=\yii\bootstrap\Html::a('Добавить вопрос', 'javascript:questAdd(' . $model->id .')' ,['class'=>'btn btn-primary'])?>



	<script type="text/x-tmpl" id="tmpl-quest">
	<?=$this->render('tmpl-quest')?>
	</script>
	<script type="text/x-tmpl" id="tmpl-option-quest">
	<?=$this->render('tmpl-option-quest')?>
	</script>

	<script type="text/x-tmpl" id="tmpl-answer">
		<?=$this->render('tmpl-answer')?>
	</script>

	<script type="text/x-tmpl" id="tmpl-add-media">
		<?=$this->render('tmpl-add-media')?>
	</script>
	<div class="collapse"><?= yii\jui\DatePicker::widget(['name' => 'attributeName']) ?></div>
</div>