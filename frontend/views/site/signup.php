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
Зарегистрируйтесь с помощью социальных сетей и вам не понадобится придумывать и запоминать пароль:
<script src="//ulogin.ru/js/ulogin.js"></script>
<div id="uLogin_10207705" data-uloginid="10207705"  data-ulogin="display=panel;fields=first_name,last_name,email,nickname,photo;lang=ru;providers=facebook,google,vkontakte,yandex,twitter,mailru;hidden=odnoklassniki,livejournal,openid,lastfm,linkedin,liveid,soundcloud,steam,flickr,uid,youtube,webmoney,foursquare,tumblr,googleplus,dudu,vimeo,instagram,wargaming;callback_hidden=uloginDo"></div>

<script>

	function uloginDo(){
		$.ajax({
			url:'/site/ulogin',
			data:{token:arguments[0],_csrf:$('meta[name="csrf-token"]').attr("content")},
			type:'post',
			dataType:'json',
			success:function(json){
				document.location.href=json.redirect;
			}
		})
	}
</script>