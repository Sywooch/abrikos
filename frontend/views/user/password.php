<h3>Смена пароля</h3>
<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$form = ActiveForm::begin([
	'options'=>[
		'onsubmit'=>'return formUpdatePassword(this)',
	],
	'validateOnChange'=>false,
	'validateOnSubmit'=>false,
]); ?>
<?php $model = Yii::$app->user->identity;?>

<?=$form->field($model, 'oldpassword')->textInput(['maxlength' => true,]) ?>
<?=$form->field($model, 'password_repeat')->textInput(['maxlength' => true,]) ?>
<?=$form->field($model, 'password')->textInput(['maxlength' => true,]) ?>
<div class="form-group">
	<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
</div>
<div class="alert alert-success hidden" id="password-form-result"></div>
<?php ActiveForm::end();?>
<script>
	function formUpdatePassword(form) {
		$.post('/user/password-update',$(form).serialize(),function (json) {
			if(json.error){
				$.each(json.error,function (i,item) {
					$('.field-user-'+i).addClass('has-error');
					$('.field-user-'+i+ ' .help-block').text(item[0]);
				})
			}else{
				$('.form-group').removeClass('has-error');
				$('.help-block').text('');
				$('#password-form-result').show().text('Пароль изменен');
			}
		},'json');
		return false;
	}
</script>


<a href="/site/request-password-reset">Восстановить забытый пароль</a>