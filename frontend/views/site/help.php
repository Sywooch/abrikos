<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

?>


<?php $form = ActiveForm::begin(['id' => 'help-modal','options'=>[
        'class'=>"modal fade",
        'tabindex'=>"-1",
        'role'=>"dialog",
        'onsubmit'=>'return helpSend(this)'
    ],
    'validateOnChange'=>false,
    'validateOnSubmit'=>false,
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
]); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Отправить сообщение Администратору</h4>
        </div>
        <div class="modal-body">
            <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'subject') ?>
            <?= $form->field($model, 'body')->textArea(['rows' => 6]) ?>
            <?= $form->field($model, 'verifycode')->widget(Captcha::className(), [
                'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
            ]) ?>
        </div>
	    <div id="help-error-message" class="alert alert-danger collapse"></div>
        <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
	        <button type="submit" class="btn btn-primary">Отправить</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<?php ActiveForm::end(); ?>

