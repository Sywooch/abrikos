<h3>Интеграция с социальными сетями</h3>
<table class="table">
	<tr><th>Network</th><th>Identity</th><th>Email</th></tr>
	<tbody id="ulogin-list"></tbody>
</table>


<script src="//ulogin.ru/js/ulogin.js"></script><div id="uLogin_10207705" data-uloginid="10207705"></div>
<script>
	$(function(){drawUlogin()})

	function drawUlogin(){
		$.getJSON('/user/ulogin-list', null,function(json){
			$('#ulogin-list').html('');
			$.each(json,function(i,item){
				$('#ulogin-list').append('<tr id="ulogin-'+item.id+'"><td>'+item.network+'</td><td>'+item.identity+'</td><td>'+item.email+'</td><td><a href="javascript:void(0)" onclick="uloginDel('+item.id+')"><span class="glyphicon glyphicon-trash">&nbsp;</span></a></td></tr>');
			})
		})
	}

	function uloginDo(){
		$.post('/user/ulogin-add',{token:arguments[0],_csrf:$('meta[name="csrf-token"]').attr("content")},function(json) { drawUlogin() });
	}

	function uloginDel(id){
		$.get('/user/ulogin-delete/',{id:id},function(json){ $('#ulogin-'+id).fadeOut(); })
	}
</script>