<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use rmrevin\yii\ulogin\ULogin;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
			<h1><?= Html::encode($this->title) ?></h1>
			<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
			<?= $form->field($model, 'username') ?>
			<?= $form->field($model, 'password')->passwordInput() ?>
			<?= $form->field($model, 'rememberMe')->checkbox() ?>
			<div style="color:#999;margin:1em 0">
				Если Вы забыли пароль попробуйте его <?= Html::a('восстановить', ['site/request-password-reset']) ?>.
			</div>

			<div class="form-group">
				<?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
			</div>

			<?php ActiveForm::end(); ?>
			<script src="//ulogin.ru/js/ulogin.js"></script>
	<div id="uLogin_10207705" data-uloginid="10207705"  data-ulogin="display=panel;fields=first_name,last_name,email,nickname,photo;lang=ru;providers=facebook,google,vkontakte,yandex,twitter,mailru;hidden=odnoklassniki,livejournal,openid,lastfm,linkedin,liveid,soundcloud,steam,flickr,uid,youtube,webmoney,foursquare,tumblr,googleplus,dudu,vimeo,instagram,wargaming;callback_hidden=uloginDo"></div>
	<script>

		function uloginDo(){
			console.log(arguments);
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
</div>

