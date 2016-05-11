
$(document).on({
	ajaxStart: function() { drawLoadingImage();	},
	ajaxStop: function() { $('#ajax-loading-image').remove(); }
});

function log() {
	console.log(arguments);
}


function drawLoadingImage() {
	$("body").append('<div id="ajax-loading-image"><img src="/images/loading.gif" /></div>');
}

function helpSend(form) {
	$.post('/site/help-post',$(form).serialize(),function (json) {
		if(json.status!='error'){
			$(form).modal('hide');
			$(form).on('hidden.bs.modal',function (e) {
				$(this).remove();
			});
			alert(json.message);
		}
		if(json.message) {
			$('#help-error-message').text(json.message).show()
		}
		form_errors(json.errors, 'contactform');
	},'json')
	return false;
}

function helpShow(type, data) {
	$.post('/site/help-show',{type:type,data:data},function (html) {
		$('body').append(html);
		$('#help-modal').modal('show').on('hidden.bs.modal',function (e) {
			$(this).remove();
		});;
	});
	return false;
}

function form_errors(errors, model) {
	if(errors){
		$.each(errors,function (name, message) {
			console.log(name,message)
			$('.field-'+model+'-'+name).addClass('has-error');
			$('.field-'+model+'-'+name+' .help-block-error').text(message);
		})
		return;
	}else{
		$('.form-group').removeClass('has-error');
		$('.help-block-error').text('');
	}
}