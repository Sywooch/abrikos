<?php
use yii\bootstrap\ActiveForm;
?>
<div class="option-block">
	<a href="<?=$model->link?>" target="_blank" class="btn btn-primary">Просмотр</a>
	<a href="/quiz/delete/<?= $model->id?>" data-method="post" data-confirm="Удалить викторину?" class="btn btn-danger">Удалить</a>
</div>

<?php $form = ActiveForm::begin(['id'=>'quiz-options-form', 'options' => ['onchange'=>'return quizUpdate(this);']]); ?>
<?php if(Yii::$app->user->isGuest):?>
	<div class="option-block">
		<?= $form->field($model, 'email')->textInput()->hint('На этот адрес будут приходить оповещения о результатах. Так же адрес будет использован для восстановления доступа к викторине') ?>
		<small>Без регистрации редактирование викторины будет доступно до перезагрузки браузера</small>
	</div>
<?php endif?>

<div class="option-block">
	<h3>Картинка викторины</h3>
	<img src="<?=$model->image?>" alt="Выберите обложку викторины" onclick="showMediaDialog(<?=$model->id?>,'quiz')" id="quiz-image-<?=$model->id?>" class="quiz-cover-image"/>
</div>

<div class="option-block">
	<h3>Опции викторины</h3>
	<?= $form->field($model, 'enabled')->checkbox()->hint('Разрешить посетителям участвовать в викторине') ?>
	<?= $form->field($model, 'send_results')->checkbox()->hint('Сообщения об ответах будут отправляться на e-mail указанный ' . (Yii::$app->user->isGuest ?'ниже':'при регистрации')) ?>
	<?= $form->field($model, 'publish')->checkbox()->hint('Разместить викторину на "главной"') ?>
	<?= $form->field($model, 'show_result')->radioList(['Никогда','Только в конце','Только после каждого ответа','После каждого и в конце'],['onchange'=>'onShowResultChange()'])->hint('') ?>
	<script>
		function onShowResultChange() {
			var obj = $('input[name=Quiz\\[show_result\\]]:checked');
			$("#options-finish").toggle([1,3].indexOf(obj.val()));
			//console.log(obj.val())
		}
	</script>
	<div id="options-finish" class="<?=in_array($model->show_result,[1,3])?'':'collapse'?>">
	<?= $form->field($model, 'result_correct_inform')->checkbox() ?>
	<?= $form->field($model, 'result_correct_show')->checkbox() ?>
	</div>
</div>
<?php ActiveForm::end(); ?>


<div class="option-block">
	<h3>Быстрая сортировка вопросов<small>подцепить и перетащить</small></h3>
	<a href="javascript:void(0)" onclick="optionQuestSort()">Обновить</a>
	<div id="options-quest-sort"></div>
</div>

<div class="option-block">
	<h3>Все ответы</h3>
	<ul>
	<?php
	$results = \frontend\modules\quiz\models\Result::find()->joinWith(['quest0'])->where(['quiz_quest.quiz'=>$model->id])->groupBy('round')->all();
	foreach ($results as $result) {
		print '<li>';
		print \yii\bootstrap\Html::a($result->date,'/quiz/console-result/?round='.$result->round);
		print ' - ' . $result->email;
		print '</li>';

	}
	?>
	</ul>
</div>
