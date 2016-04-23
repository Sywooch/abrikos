$(function(){
	questList();

})
function getQuizId() {
	return $('#quiz-id').val();
}

function questAdd() {
	var id = getQuizId();
	$.getJSON('/quiz/quest-add/'+id,null,function (json) {
		$('#quest-list').append(tmpl("tmpl-quest", json));
		$('html, body').animate({
			scrollTop: $('#quest-'+json.quest.id).offset().top - 50
		}, 1000);
	})
}

function questList() {
	var id = getQuizId();
	$.getJSON('/quiz/quest-list/'+id,null,function (json) {
		$('#quest-list').text('');
		$.each(json,function (i,item) {
			$('#quest-list').append(tmpl("tmpl-quest", item));
		});
		$('#quest-list').sortable({update:function(){
			postQuestSort();
		}});

	})
}

function postQuestSort() {
	var id = getQuizId();
	var orderArray = [];
	$.each($('.quest-wrapper'),function (order,obj) {
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

function formSubmit(form){
	var id = getQuizId();
	$.post('/quiz/update/'+id,$(form).serialize(),function(json){
		$('.field-quiz-'+name).removeClass('has-error');
		$('.field-quiz-'+name+' .help-block').text('');
		if(json.errors){
			$.each(json.errors,function (name, message) {
				$('.field-quiz-'+name).addClass('has-error');
				$('.field-quiz-'+name+' .help-block').text(message);
			})
		}
	},'json');
}

function questUpdate(id) {
	$.post('/quiz/quest-update/'+id,$('#quest-form-'+id).serialize(),function (data) {

	});
}

function answerUpdate(id) {
	$.post('/quiz/answer-update/'+id,$('#answer-form-'+id).serialize(),function (data) {

	});
}

function answerAdd(quest) {
	$.post('/quiz/answer-add/'+quest,null,function (json) {
		$('#answer-quest-'+quest).append(tmpl("tmpl-answer", json));
	},'json');
}

function answerDelete(id) {
	$.post('/quiz/answer-delete/'+id,null,function () {
		$('#answer-'+id).fadeOut()
	})
}

function questDelete(id) {
	$.post('/quiz/quest-delete/'+id,null,function () {
		$('#quest-'+id).fadeOut()
	})
}

function showMediaDialog(id,type) {
	$('body').append(tmpl("tmpl-add-media", {id:id, type:type}));
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
		if (size < 0.05 && object=='quest') {
			alert('Слишком маленькое изображение');
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
			if(myXhr.upload){ // Check if upload property exists
				myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // For handling the progress of the upload
			}
			return myXhr;
		},
		//Ajax events
		//beforeSend: beforeSendHandler,
		success: function (json) {
			$('#'+object+'-image-'+id).attr('src',json.image + '?'+new Date().getTime()).fadeIn();
		},
		error: function (a,b,c) {
			alert(a.responseText);
			console.log(a);console.log(b);console.log(c);
		},
		beforeSend: function() {
			$('progress').show();
		},
		complete: function() {
			$('progress').hide();
			$('#btn-media-dialog-close').click();
		},
		// Form data
		data: formData,
		dataType:'json',
		//Options to tell jQuery not to process data or worry about content-type.
		cache: false,
		contentType: false,
		processData: false
	});
}

function doYoutube(url) {
	$('#add-media-dialog').modal('toggle');
	$('#youtubeModal').modal('toggle');
}

function doQuestImageUrl(urlImage,type,id) {
	//$('#btn-media-dialog-close').click();
	var formData = new FormData();
	formData.append('url',urlImage);
	$('progress').show();
	$.ajax({
		url: '/quiz/upload-url/'+id+'?type='+type,  //Server script to process data
		type: 'POST',
		//Ajax events
		//beforeSend: beforeSendHandler,
		success: function (json) {
			$('#quest-image-'+id).attr('src',json.image + '?'+new Date().getTime());

			//console.log(json);
		},
		error: function (a,b,c) {
			alert(a.responseText);
		},
		beforeSend: function() {
			$('progress').show();
		},
		complete: function() {
			$('progress').hide();
			$('#btn-media-dialog-close').click();
		},
		// Form data
		data: formData,
		dataType:'json',
		//Options to tell jQuery not to process data or worry about content-type.
		cache: false,
		contentType: false,
		processData: false
	});
}

function progressHandlingFunction(e){
	if(e.lengthComputable){
		$('progress').attr({value:e.loaded,max:e.total});
		var percentComplete = e.loaded / e.total;
		console.log(percentComplete);
	}
}