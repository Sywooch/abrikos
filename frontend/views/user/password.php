<h3>Смена пароля</h3>
<form onsubmit="passwordChange(this); return false" class="form-inline">
	Старый пароль <input name="password[old]" type="password">  <br/>
	Новый пароль<input name="password[new]" type="password">    <br/>
	Подтверждение <input name="password[new2]" type="password">      <br/>
	<input type="submit" value="Сменить пароль">
</form>

<script>
	function passwordChange(form){
		obj = $(form);
		data = obj.serialize();
		alert = $('#password-alert');
		alert.attr('class','alert');
		alert.hide();
		obj.children('[type=password]').val('')
		$.post('/user/password-change',data,function(data){

			if(data=='1'){
				alert.html('Пароль изменен');
				alert.addClass('alert-success');
				alert.fadeIn();
			}else{
				alert.html('Ошибка: '+ data);
				alert.addClass('alert-danger');
				alert.fadeIn();
			}
		})
		return false;
	}
</script>
<div id="password-alert" class="alert collapse"></div>
<a href="/site/request-password-reset">Восстановить забытый пароль</a>