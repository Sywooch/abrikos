/**
 * Created by abrikos on 25.12.15.
 */

function winnerRegister(form){
	if(!checkPhone()){return false;}
	$.post('/miner/winner',$(form).serialize(),function(data){
		$('#winner-data').fadeOut();
		json = JSON.parse(data);
		if(json.error){ alert(json.error); return false;}
	});
	return false;
}
function checkPhone(){
	obj = $('#winner-phone');
	btn = $('#winner-submit');
	phone = obj.val();
	if(phone.match(/^9[0-9]{9}/)){
		btn.show()
		obj.removeClass('btn-danger');
		console.log('OK');
		return true
	}{
		btn.hide()
		obj.addClass('btn-danger');
		console.log('ERR');
		return false;
	}
}

var timer = 0;
var timerId;
var clicked = false;
function driveTime(){
	timer = timer +1;
	$('#timer').html(timer);
}


//$(function(){          fillTable(9,16,16);    })
function fillTable($rowsTotal, $colsTotal, $bombs){
	$('#winner').html('');
	$('#winner-data').hide();
	$('#timer').show();
	var table = $('#miner');
	clicked = false;
	table.html('');
	for(row=0;row<$rowsTotal;row++){
		table.append('<tr id="row'+row+'"></tr>');
		for(col=0;col<$colsTotal;col++){
			$('#row'+row).append('<td id="row'+row+'-col'+col+'" data="row='+row+'&col='+col+'" class="mineCell initial"></td>')
		}
	}

	$.get('/miner/new',{rows:$rowsTotal,cols:$colsTotal,bombs:$bombs});
	$('.mineCell').bind("contextmenu",function(e){
		var cell = $(this);
		if(!cell.hasClass('initial')){return 0;}
		cell.toggleClass('flag');
		return false;
	});
	$('.mineCell').click(function(obj){
		var cell = $(this);
		if(cell.hasClass('flag')){return 0;}
		$.getJSON('/miner/check',cell.attr('data'),function(json){
			if(!clicked) {
				timer = 0;
				timerId = setInterval(driveTime,1000);
				clicked = true;
				if (json.cheater) {
					console.log(json.cheater.length);
					$.each(json.cheater, function (r, cell) {
						if (cell.status == 'Mine') {
							$('#row' + cell.row + '-col' + cell.col).addClass('mine');
							//$('#row' + cell.row + '-col' + cell.col).html('-'+cell.idx+'-');
						}
					});
				}
			}
			$.each(json.opencells,function(r,cell){
				var td = $('#row'+cell.row+'-col'+cell.col);
				if (json.gameover) {
					$('.mineCell').unbind('click');
					$('.mineCell').unbind('contextmenu');
					td.addClass('mine').html('*');
					$('#winner').html('Бабах!!!');
					clearInterval(timerId);
				} else {
					if(!td.hasClass('flag')) {
						td.unbind('click');
						td.addClass(cell.status).html(cell.count)
						td.removeClass('initial');
					}
				}
			})
			if (json.winner.time) {
				$('#winner').html('Победа! Время:' + json.winner.time + ' сек. Ходов: '+json.winner.turn);
				$('#timer').hide();
				//checkPhone();
				$('#winner-data').fadeIn();

				clearInterval(timerId);
			}
			console.log(json.winner);
			//$('#test').html(JSON.stringify(json.test))
		})

	})

}





function cheater(){
	$.getJSON('/miner/cheat',null,function(json){ putMines(json); });
}

function putMines(mines){
	$.each(mines, function(row,cols){
		$.each(cols, function(col,val){
			//$('#row'+row+'-col'+col).html('x');
			$('#row'+row+'-col'+col).addClass('mine')
		})
	})
}
