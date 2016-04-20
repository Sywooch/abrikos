<?php
\app\modules\seawar\SeawarAsset::register($this);
$mytable = new \app\modules\seawar\models\SeaTable();
$mytable->drawTable();
//$mytable->randomFill();
?>

<a href="javascript:void(0)" onclick="test()">test</a>
<script>
	function test() {
		$.getJSON('/seawar/test',null,function(json){
			$('.cell').text('').removeClass('ship').attr('style','background-color:none')
			$.each(json.vert,function (i,item) {
				$('#cell-'+item.x+'-'+item.y).text(0);
			})
			$.each(json.hor,function (i,item) {
				$('#cell-'+item.x+'-'+item.y).append(1);
			})

			$.each(json.ships,function (i,item) {
				$('#cell-'+item.x+'-'+item.y).addClass('ship').text(item.shipId).attr('style',item.size==4?'background-color:blue':'');
			})
			$('#alert').show().text(JSON.stringify(json.e));
		})
	}
</script>

<div id="alert" class="alert-danger alert collapse"></div>
