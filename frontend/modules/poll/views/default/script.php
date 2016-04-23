function loadScript(url, callback)
{
// Adding the script tag to the head as suggested before
var head = document.getElementsByTagName('head')[0];
var script = document.createElement('script');
script.type = 'text/javascript';
script.src = url;

// Then bind the event to the callback function.
// There are several events for cross browser compatibility.
script.onreadystatechange = callback;
script.onload = callback;

// Fire the loading
head.appendChild(script);
}

var myPrettyCode = function() {
	$.ajax({
	url:'http://<?=$_SERVER['SERVER_NAME']?>/poll/draw/<?=$id?>',
	dataType: 'jsonp',
	success:function(json){
		$('#poll-<?=$id?>').html(json.html);

	},
	error:function(a,b,c){
		console.log(a,b,c);
	}
	});
}

loadScript("//code.jquery.com/jquery-1.10.2.js", myPrettyCode);