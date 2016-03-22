<h3>Адрес e-mail</h3>
<form onsubmit="return changeEmail(this)">
	<input value="<?=Yii::$app->user->identity->email;?>" name="email" />
	<input type="submit" value="Изменить e-mail">
</form>
<div id="email-success" class="alert alert-success collapse"></div>
<div id="email-error" class="alert alert-danger collapse"></div>
<script>
	function changeEmail(form){
		console.log($(form).serialize());
		$.ajax({
			url:'/user/email-change',
			data:$(form).serialize(),
			type:'post',
			dataType:'json',
			success:function(json){
				$('#email-error').html('').hide();
				$('#email-success').html('E-mail изменен').show();
				$.each(json.error,function(field,msg){
					$('#email-success').hide();
					$('#email-error').append(msg).show();
				})
		}})
		return false;
	}
</script>
