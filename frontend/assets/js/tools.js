function getData(type, val) {
	switch (type){
		case 'geoip':
		case 'nslookup':
		case 'whois':
		case 'ping':
			var data = {address:val};
			break;
	}
	$.getJSON('/tools/json/',{type:type, data:data},function (json) {
		$('#tools-result').html('');
		$.each(json,function (i,item) {
			console.log(i, item)
			$('#tools-result').append('<dt>'+i+'</dt><dd>'+item+'</dd>');
		});
	})
}

$(function () {
	$('#tools-menu input').change(function (e) {
		console.log($(this))
		getData($(this).data('type'),$(this).val());
	});
	$('#tools-menu button').click(function () {
		$(this).parent().children('input').change();
	})
})