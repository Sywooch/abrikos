/**
 * Created by abrikos on 05.05.16.
 */
var MODULE_NAME = 'reco';
var CURRENT_TOUR;

$(function () {
	//$.post('/'+MODULE_NAME+'/search-debug',null,function (json) {	$('#search-result').html(tmpl("tmpl-search-result", json));	},'json');
	CURRENT_TOUR = 0;
	tourDraw(TOURS[CURRENT_TOUR]);
	$('#tours-list-container').sortable({update:function(){
		tourSort('.tour-view');
	}});
});

function tourDraw(tour) {
	$('#tour-container').html(tmpl("tmpl-tour-form", tour));
	$('#input-word-search').val(tour.tour.answer);
	toursListDraw();
}

function toursListDraw() {
	$('#tours-list-container').text('');
	$.each(TOURS,function (i,data) {
		$('#tours-list-container').append(tmpl("tmpl-tour-list-item", data));
		$('#tour-view-'+data.tour.id).toggleClass('active',data.tour.id == getTourId() )
	});
}

function questMainPicture(order) {
	var id =getTourId();
	$.getJSON(url('quest-picture',id),{order:order},function (json) {
		$('#quest-image').attr('src', json.src);
	})
}

function tourSort(container) {
	var id = getQuestId();
	var orderArray = [];
	$.each($(container),function (order,obj) {
		orderArray.push({id:$(obj).data('id') , order:order});
	});
	$.post(url('tour-sort',id),{sort:orderArray},function (json) {
		TOURS = JSON.parse(json);
	});
}

function findTour(id) {
	return TOURS.filter(function (data) {
		return data.tour.id == id;
	})[0];
}

function tourSelect(id) {
	var tour = findTour(id);
	tourDraw(tour);
}

function tourPager(direction) {
	var n = CURRENT_TOUR + direction;
	if(n>=TOURS.length){ n = 0;}
	if(n<0){ n = TOURS.length-1;}
	CURRENT_TOUR = n;
	tourDraw(TOURS[n]);
}

function tourAdd() {
	$.post(url('tour-create',getQuestId()),null,function (json) {
		TOURS.push(json);
		tourDraw(json);
	},'json')
}


function formSearchByWord(form,type) {
	$.post(url('search','?type='+type),$(form).serialize(),function (json) {
		$('#search-result').html(tmpl("tmpl-search-result", json));
	},'json');
	return false;
}

function imageAdd(id) {
	var container = $('#images-table');
	var image = $('#image-search-'+id);
	container.append(tmpl("tmpl-choosen-image", {id:id, src:image.attr('src')}));
	if(container.children().length>4){
		var img = container.children('.status-0').first();
		if(!img.length) {
			img = container.children().first();
		}
		$('#images-removed').prepend(img);
	}
	$('#button-choosen-download').fadeOut();
	toursListDraw();
}

function imageChoosenDownload(id) {
	var formData = new FormData();
	$('#images-table .image-choosen').each(function (i,item) {
		formData.append('src[]',$(item).attr('src'));
	})
	$.ajax({
		url:url('choosen-download',id),
		data:formData,
		success:function () {
				$('#images-table').text('');
				$.each(json.images,function (i,image) {
					$('#images-table').append(tmpl("tmpl-choosen-image", image));
				});
				$('#button-choosen-download').fadeOut();
			},
		type:'post',
		dataType:'json',
		processData: false,
		contentType: false,
	});
}

function questUpdate(form , id){
	$.post(url('update',id),$(form).serialize(),function(json){
		$('.field-'+MODULE_NAME+'-'+name).removeClass('has-error');
		$('.field-'+MODULE_NAME+'-'+name+' .help-block-error').text('');
		form_errors(json.errors, MODULE_NAME);
	},'json');
	return false;
}

function tourUpdate(form,id) {
	$.post(url('tour-update',id),$(form).serialize(),function(json){
		$('#tour-error-'+id).html('').hide();
		if(json.errors){
			$('#tour-error-'+id).show();
			$.each(json.errors,function (name,item) {
				$('#tour-error-'+id).append(item);
			})
			return;
		}
		$('#form-letters-'+id).text(json.tour.shuffle);
		var t =findTour(id);
		TOURS[t.tour.sort] = json;
		toursListDraw();
	},'json');
	return false;
}


function showMediaDialog(item,id) {
	var index = $('div.image-choosen-wrap').index($(item).parent());
	$('body').append(tmpl("tmpl-add-media", {id:id, index:index}));
	$('#progress-wrap').html('<progress class="collapse"></progress>');
	$('#add-media-dialog').modal().on('hidden.bs.modal', function (e) {
		$('#add-media-dialogs-wrapper').remove();
	})
}

function doUpload(file, type, imageid, index) {
	var formData = new FormData();
	if( (typeof file === "object") && (file !== null) ) {
		var size = file.files[0].size / 1024 / 1024;
		//console.log(parseInt(size) > 2 * 1024 * 1024);
		var ext = $(file).val().split('.').pop().toLowerCase();
		if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
			alert('Выбранный файл не является изображением!');
		}
		if (size > 5) {
			alert('Размер выбранного изображения превышает 2Мб');
			return;
		}
		formData.append('file',$(file)[0].files[0]);
	}else{
		formData.append('url',file);
	}

	$.ajax({
		url: url('upload',getTourId()+'?type='+type+'&index='+index),  //Server script to process data
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
			$('#image-choosen-'+imageid).attr('src',json.image + '?'+new Date().getTime()).fadeIn();
			toursListDraw();
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


function progressHandlingFunction(e){
	if(e.lengthComputable){
		$('progress').attr({value:e.loaded,max:e.total});
		var percentComplete = e.loaded / e.total;
	}
}
function url(action,params) {
	return '/' + MODULE_NAME + '/' + action +'/'+params;
}

function getQuestId() {
	return $('#quest-data').data('id');
}

function getTourId() {
	return $('#tour-data').data('id');
}
