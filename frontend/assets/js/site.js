
$(document).on({
	ajaxStart: function() { drawLoadingImage();	},
	ajaxStop: function() { $('#ajax-loading-image').remove(); }
});


function drawLoadingImage() {
	$("body").append('<div id="ajax-loading-image"><img src="/images/loading.gif" /></div>');
}

