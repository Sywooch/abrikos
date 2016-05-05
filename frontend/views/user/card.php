<?php
/**
 * Created by PhpStorm.
 * User: abrikos
 * Date: 04.05.16
 * Time: 13:17
 */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

?>
<h3>Смена имени и фамилии</h3>
<div class="well">
	Внимание! Имя, Фамилия и Аватар обновляются автоматически при входе через Facebook
</div>
<?php $form = ActiveForm::begin([
	'options'=>[
		'enctype'=>'multipart/form-data',
		'onsubmit'=>'return formUpdateProfile(this)',
	],
	'validateOnChange'=>false,
	'validateOnSubmit'=>false,
]); ?>
<?php $model = Yii::$app->user->identity;?>

<?=$form->field($model, 'first_name')->textInput(['maxlength' => true,]) ?>
<?=$form->field($model, 'last_name')->textInput(['maxlength' => true,]) ?>
<?=$form->field($model, 'username')->textInput(['disabled'=>true])?>
<?=$form->field($model, 'email')->textInput(['disabled'=>true])?>

<div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end();?>

<script>
	function formUpdateProfile(form) {
		$.post('/user/card-update',$(form).serialize(),function (json) {
			if(json.error.length>0){
				$.each(json.error,function (i,item) {
					$('.field-user-'+i).children('help-block').html(item);
				})
			}else{
				$.each($('.form-control'),function (i,input) {
					$('#card-' + input.id).text(input.value);
				})
			}
		},'json');
		return false;
	}
</script>
