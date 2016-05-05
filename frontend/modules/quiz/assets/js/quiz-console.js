$(function(){
	questList();

})
function getQuizId() {
	return  $('#quiz-id').val();
}

function questAdd() {
	var id = getQuizId();
	$.getJSON('/quiz/quest-add/'+id,null,function (json) {
		$('#quest-list').prepend(tmpl("tmpl-quest", json));
		$('html, body').animate({
			scrollTop: $('#quest-'+json.quest.id).offset().top - 50
		}, 1000);
	});
	checkQuiz();
}

function checkQuiz() {
	var id=getQuizId();
	$.getJSON('/quiz/check/'+id,null,function (json) {
		if(json){
			$('#navbar-bottom').fadeIn();
			$('#console-log').html(json.name + ' - Вопрос не имеет правильного ответа.  <a href="#quest-' + json.id + '">Перейти</a>' );
		}else{
			$('#navbar-bottom').fadeOut();
		}
	})
}

function optionQuestSort() {
	$('#options-quest-sort').html('');
	$.each($('.quest-textarea-name'),function (order,obj) {
		var item = {quest:{}};
		item.quest.id = $(obj).attr('data');
		item.quest.name =obj.value;
		$('#options-quest-sort').append(tmpl("tmpl-option-quest",item));
		//console.log(obj.value);
		//orderArray.push(element);
	});

}

function questList() {
	var id = getQuizId();
	$.getJSON('/quiz/quest-list/'+id,null,function (json) {
		$('#quest-list').text('');
		$.each(json,function (i,item) {
			$('#quest-list').prepend(tmpl("tmpl-quest", item));
		});

		$('#quest-list').sortable({update:function(){
			postQuestSort('.quest-wrapper');
		}});
		optionQuestSort();
		$('#options-quest-sort').sortable({update:function(){
			postQuestSort('.options-quest-wrapper');
		}});

	})
}

function postQuestSort(container) {
	var id = getQuizId();
	var orderArray = [];
	$.each($(container),function (order,obj) {
		var element = {};
		element.id = $(obj).attr('data');
		element.order = order;
		orderArray.push(element);
	});
	$.post('/quiz/quest-sort/'+id,{sort:orderArray});
	//console.log(orderArray);
}

function questSort(id,dir) {
	var obj = $('#quest-'+id);
	if(dir){
		obj.prev().before(obj);
	}else{
		obj.next().after(obj);
	}
	$('html, body').animate({
		scrollTop: obj.offset().top - 50
	}, 1000);

	postQuestSort();
}

function quizUpdate(form){
	var id = getQuizId();
	$.post('/quiz/update/'+id,$(form).serialize(),function(json){

		$('.field-quiz-'+name).removeClass('has-error');
		$('.field-quiz-'+name+' .help-block').text('');
		if(json.errors){
			$.each(json.errors,function (name, message) {
				$('.field-quiz-'+name).addClass('has-error');
				$('.field-quiz-'+name+' .help-block').text(message);
			})
		}else{
			$('.form-group').removeClass('has-error');
			$('.help-block').text('');
		}
	},'json');
	return false;
}

function questUpdate(id) {
	$.post('/quiz/quest-update/'+id,$('#quest-form-'+id).serialize(),function (data) {
		optionQuestSort();
	});
}

function answerUpdate(id, input) {
	$.post('/quiz/answer-update/'+id,{text:$(input).val()},function (data) {

	});
}

function answerAdd(quest) {
	$.post('/quiz/answer-add/'+quest,null,function (json) {
		$('#answer-quest-'+quest).append(tmpl("tmpl-answer", json));
	},'json');
}

function answerDelete(id) {
	$.post('/quiz/answer-delete/'+id,null,function (json) {
		if(json.id==id){
			$('#answer-'+id).fadeOut();
		}else{
			alert(json.error);
		}
		
	},'json')
}

function questDelete(id) {
	$.post('/quiz/quest-delete/'+id,null,function (json) {
		if(json.id==id){
			$('#quest-'+id).fadeOut();
			$('#quest-'+id).remove();
			optionQuestSort();
			$('#navbar-bottom-collapse').fadeOut();
		}else{
			alert(json.error);
		}
	},'json')
}

function showMediaDialog(id,object) {
	$('body').append(tmpl("tmpl-add-media", {id:id, object:object}));
	$('#progress-wrap').html('<progress class="collapse"></progress>');
	$('#add-media-dialog').modal().on('hidden.bs.modal', function (e) {
		$('#add-media-dialogs-wrapper').remove();
	})
}

function doUpload(file, type, id, object) {
	var formData = new FormData();
	if( (typeof file === "object") && (file !== null) ) {
		var size = file.files[0].size / 1024 / 1024;
		//console.log(parseInt(size) > 2 * 1024 * 1024);
		var ext = $(file).val().split('.').pop().toLowerCase();
		if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
			alert('Выбранный файл не является изображением!');
		}
		if (size > 2) {
			alert('Размер выбранного изображения превышает 2Мб');
			return;
		}
		if (size < 0.05 && ['quest','quiz'].indexOf(object)!=-1) {
			alert('Слишком маленькое изображение . ');
			return;
		}
		formData.append('file',$(file)[0].files[0]);
	}else{
		formData.append('url',file);
	}

	$.ajax({
		url: '/quiz/upload/'+id+'?type='+type+'&object='+object,  //Server script to process data
		type: 'POST',
		xhr: function() {  // Custom XMLHttpRequest
			var myXhr = $.ajaxSettings.xhr();
			if(myXhr.upload && type=='disk'){ // Check if upload property exists
				myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // For handling the progress of the upload
			}
			return myXhr;
		},
		//Ajax events
		//beforeSend: beforeSendHandler,
		success: function (json) {
			$('#'+object+'-image-'+id).attr('src',json.image + '?'+new Date().getTime()).fadeIn();
			if(object=='answer') {
				$('#answer-image-' + id).show();
				$('#answer-text-' + id).hide();
				$('#answer-image-button-' + id).show();
				//$('#answer-text-button-' + id).hide();
			}
		},
		error: function (a,b,c) {
			alert(a.responseText);
		},
		beforeSend: function() {
			$('progress').show();
		},
		complete: function() {
			$('#btn-media-dialog-close').click();
			$('#add-media-dialogs-wrapper').remove();
		},
		// Form data
		data: formData,
		dataType:'json',
		//Options to tell jQuery not to process data or worry about content-type.
		cache: false,
		contentType: false,
		processData: false
	});
	return false;
}

function doYoutube(url) {
	$('#add-media-dialog').modal('toggle');
	$('#youtubeModal').modal('toggle');
}

function progressHandlingFunction(e){
	if(e.lengthComputable){
		$('progress').attr({value:e.loaded,max:e.total});
		var percentComplete = e.loaded / e.total;
	}
}

function answerCorrect(id,obj) {
	$.getJSON('/quiz/answer-correct/'+id,null,function (json) {
		$('#answer-'+id).toggleClass('answer-correct',json.correct);
		if(json.correct){
			$(obj).removeClass('glyphicon-unchecked').addClass('glyphicon-check');
		}else{
			$(obj).addClass('glyphicon-unchecked').removeClass('glyphicon-check');
		}
	});
	checkQuiz();
}

function pictureHide(id) {
	$.getJSON('/quiz/answer-text/'+id,null,function (json) {
		$('#answer-image-'+id).hide();
		$('#answer-text-'+id).show();
		$('#answer-image-button-'+id).hide();
		//$('#answer-text-button-'+id).show();

	})
}