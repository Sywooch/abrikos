function getQuizId() {
	return $('#quiz-id').val();
}

function questView() {
	var id=getQuizId();
	$.getJSON('/quiz/process-view/'+id,null,function (json) {
		$('#quest-data').html(tmpl("quest-view", json));
	})
}


function questSubmit(id,form) {
	$('#pretendent-form-email').val($('#pretendent-email').val());
	$.post('/quiz/process-answer/'+id,$(form).serialize(),function (json) {
		var alert = $('#quest-alert');
		alert.fadeOut();
		console.log(json)
		switch (json.status){
			case 'done':
				document.location.href = '/quiz/process-done/'+getQuizId()+'?round='+json.round; return;
				break;
			case 'error':
				alert.text(json.message).fadeIn()
				break;
			case 'show_result':
				$.each(json.answers,function (i,answer) {
						$('#answer-item-' + answer.id).addClass(answer.correct? 'answer-correct':'answer-wrong');
				});
				$('#answer-choose-header').text('Ответ принят. Посмотрите верные ответы:');
				$('#quest-description').text(json.description ? 'Комментарий к ответу:' + json.description:'');
				$('#btn-quest-submit').hide();
				$('#btn-quest-next').fadeIn();
				break;
			default:
				$('#quest-data').html(tmpl("quest-view", json));
		}
	},'json');
	return false;
}

