/**
 * Created by abrikos on 05.05.16.
 */
var MODULE_NAME = 'reco';
$(function () {
	getTourData();
})

function getTourData() {
	var id = $('#start-tour-id').val();
	if(id>0){
		var action = url('tour-current',id);
	}else{
		var action = url('tour-next',getQuestId());
	}
	$.getJSON(action,null,function (json) {
		if(!json.id){
			switch (json.status){
				case 'completed':
					$('#view-container').html(tmpl('tmpl-tour-done', json));
					break;
				default:
					$('#view-container').html(json.message);
					break;
			}
		}else{
			$('#view-container').html(tmpl('tmpl-tour-view', json));
		}
		
		$('.tour-button').hide();
		$('#answer-result').hide()
		$('#btn-abuse').data('var',json)
	})
}


function chooseLetter(id) {
	$('.answer-result').hide();
	var button = $('#letter-'+id);
	$('.right-letters-container').each(function () {
		var container =$(this);
		if(container.children().length==0){
			/*
			button.animate({
				opacity: 0.4,
			}, {
				duration:300,
				complete:function () {
					$(this).css('opacity',1);
					container.append(this);
					checkAnswer();
				}
			} );
			*/
			container.append(button);
			checkAnswer();
			return false;
		}
	})
}

function clearAnswer() {
	$('#answer-container .letter-container').each(function (i,item) {
		log(item)
		$(item).click();
	})
}

function letterReturn(container) {
	var button = $(container).children()[0];
	$('#letter-container-'+$(button).data('id')).append(button);
	$('#answer-result').hide();
}

function checkAnswer() {
	var answer ='';
	$('.right-letters-container').each(function () {
		answer += $($(this).children()[0]).text();
	});
	if(answer.length == $('.right-letters-container').length ){
		$.post(url('answer',getTourId()),{answer:answer},function (json) {
			switch (json.status){
				case 'ok':
					$('#answer-ok').show();
					$('#answer-wrong').hide();
					break;
				case 'wrong':
					$('#answer-ok').hide();
					$('#answer-wrong').show();
					break;
			}
			
		},'json')
	}
}

function url(action,params) {
	return '/' + MODULE_NAME + '/' + action + '/' + params;
}

function getTourId() {
	return $('#tour-id').val()
}
function getQuestId() {
	return $('#quest-id').val()
}
