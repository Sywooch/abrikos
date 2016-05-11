function start(){
	$.post('/cows/start',{'playerName':$('#playerName').val()},function(){
		$('#gameForm').show();
		$('#gameContainer').removeClass('hide');
		$('#gameResult').html('');
		$('#startButton').hide();
		$('#toptable').hide();
	});
}

function testNumber(){
	$.ajax({
		url:'/cows/turn',
		data:$('#gameForm').serialize(),
		type:'post',
		dataType:'json',
		success:function(json){
			$('#testField').val('');
			if(json.bulls<4){
				$('#gameResults').append('<tr class="counting"><td></td><td>'+json.test+'</td><td>'+json.bulls+'</td><td>'+json.cows+'</td></tr>');
				$('#clickDigits td').show();
			}else{
				$('#startButton').show();
				$('#gameForm').hide();
				$('#gameResults').append('<tr class="counting"><td></td><td>'+json.test+'</td><td colspan="2" style="color:red">Вы угадали число!</td></tr>');
			}
			$('#error').html('');
		},
		error:function(err){$('#error').html(err.responseText);}
	})
}

function addDigit(i){
	var digit = $('#digit'+i);
	var input = $('#testField');
	if(input.val().length<4) {
		digit.hide();
		input.val(input.val()+i);
	}
}

