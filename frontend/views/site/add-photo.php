<?php use yii\bootstrap\ActiveForm;

$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'], 'enableAjaxValidation'=>false, 'enableClientScript'=>false, 'enableClientValidation'=>false]) ?>

<?= $form->field($model, 'imageUrl')->textInput() ?>
<?= $form->field($model, 'imageFile')->fileInput() ?>
<?= $form->field($model, 'text')->textInput() ?>

	<button>Submit</button>

<?php ActiveForm::end() ?>