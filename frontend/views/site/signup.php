<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Пожалуйста заполните следующие поля для регистрации:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                <?= isset($session['openid']) ? $form->field($model, 'username',['options'=>['class'=>'collapse']])->hiddenInput() : $form->field($model, 'username') ?>
                <?= $form->field($model, 'email') ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <div class="form-group">
                    <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<div>
Зарегистрируйтесь с помощью социальных сетей и вам не понадобится придумывать и запоминать пароль:
</div>
<?php
$fb = \frontend\controllers\FbController::fbinit();
$helper = $fb->getRedirectLoginHelper();
$permissions = ['email', 'public_profile','user_about_me']; // optional
$loginUrl = $helper->getLoginUrl('https://'.$_SERVER['SERVER_NAME'].'/fb/callback', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '" title="Войти с помощью  Facebook"><img src="/images/fb.png" alt="facebook login" /></a>';

?>
