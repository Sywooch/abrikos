
var timer = 0;
var timerId;
var clicked = false;
function driveTime(){
	timer = timer +1;
	$('#timer').html(timer);
}

function drawPanel2(s,x,y,WIDTH,HEIGHT,borderWidth,inverse, row,col){
	var tl1 = [x, y];
	var dl1 = [x,y+HEIGHT];
	var dr1 = [x+WIDTH,y+HEIGHT];
	var dr2 = [x+WIDTH-borderWidth,y+HEIGHT-borderWidth];
	var dl2 = [x+borderWidth, y+HEIGHT - borderWidth];
	var tl2 = [x+borderWidth, y+borderWidth];
	var tr1 = [x+WIDTH,y];
	var tr2 = [x+WIDTH-borderWidth,y+borderWidth];
	var dark = '#666';
	var light = '#eee';
	if(inverse){
		var tmp = dark;
		dark = light;
		light = tmp;
	}

	var bottom = s.rect(x, y,WIDTH,HEIGHT);
	bottom.attr({
		fill: 'silver',
		class:'bottom',
		stroke: 'black',
		id:'bottom-'+row+'-'+col,
	});


	var border1 = s.polyline(dl1,tl1,tr1,tr2,tl2,dl2);
	border1.attr({
		fill: dark,
	})
	var border2 = s.polyline(dl1,dr1,tr1,tr2,dr2,dl2);
	border2.attr({
		fill: light,
	})
	var top = s.rect(x, y,WIDTH,HEIGHT);
	top.attr({fill:'silver'});

	var cell = s.group(top,border1,border2);
	cell.attr({
		id:'cell-'+row+'-'+col,
		row:row,
		col:col,
		class:'miner-cell',
	});
}


$(function(){
	var cellH = 40;
	var cellW = cellH;

	var COLS = 9;
	var ROWS = 9;
	var borderWidth = 5;

	var HEIGHT = cellH * (ROWS + 1) + borderWidth*6;
	var WIDTH = cellW * COLS + borderWidth*5;
	$('#svg-container').height(HEIGHT).width(WIDTH);
	var s = Snap('#svg-container');
	var panelWidth = WIDTH-borderWidth*2;
	drawPanel2(s,0,0,WIDTH,HEIGHT,borderWidth,true);
	drawPanel2(s,borderWidth*2,borderWidth*2,panelWidth-borderWidth*2,cellH,borderWidth/2,false);
	drawPanel2(s,borderWidth*2,borderWidth+cellH+borderWidth*2,panelWidth-borderWidth*2,cellH * (ROWS)+borderWidth,borderWidth/2,false);
	//drawPanel2(s,60,10,cellH,cellH,borderWidth/2,true);
	var fieldTopX = borderWidth*2.5;
	var fieldTopY = borderWidth+cellH+borderWidth*2.5;
	var fieldBottomX = panelWidth - borderWidth/2;
	var fieldBottomY = HEIGHT - borderWidth*2.5;
	var BOMBPROTO = $('#bomb-proto').clone();
	$('#bomb-proto').hide();
	var FLAGPROTO = $('#flag-proto').clone();
	$('#flag-proto').hide();


	for(row = 0; row<ROWS; row++){
		for(col = 0; col<COLS; col++){
			var tx = fieldTopX + cellW * col;
			var ty = fieldTopY + cellH*row;
			var bord = 4;
			drawPanel2(s,tx,ty,cellW,cellH,bord,true, row,col);
		}
	}

	$('.miner-cell').bind('contextmenu',function(event){
		// Avoid the real one
		event.preventDefault();

		// Avoid the event from bubbling up to parent
		event.stopPropagation();
		var cell = $(this);
		flag = FLAGPROTO.clone().appendTo('#svg-container');
		flag.attr({'x':cell[0].previousElementSibling.x.baseVal.value, 'y':cell[0].previousElementSibling.y.baseVal.value});
		if(!cell.hasClass('initial')){return 0;}
		cell.toggleClass('flag');
		console.log(cell);
		return false;
	})

	$('.miner-cell').click( function(e){
		var cell = $(e.target.parentElement);
		switch(cell.attr('class')){
			case 'miner-cell':
				$(cell).fadeOut();
					console.log(cell.attr('row'));
					if(cell.hasClass('flag')){return 0;}
					$.getJSON('/minerGame/check',{row:cell.attr('row'), col:cell.attr('col')},function(json){
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
							var td = $('#bottom-'+cell.row+'-'+cell.col);
							var td1 = $('#cell-'+cell.row+'-'+cell.col);
							td1.fadeOut();
							if (json.gameover) {
								$('.miner-cell').unbind('click');
								$('.miner-cell').unbind('contextmenu');
								td.attr('class','mine');
								bomb = BOMBPROTO.clone().appendTo('#svg-container');
								bomb.attr({'x':td[0].x.baseVal.value, 'y':td[0].y.baseVal.value});
								$('#winner').html('Бабах!!!');
								clearInterval(timerId);
							} else {
								console.log(td)
								if(!td.hasClass('flag')) {
									td.unbind('click');
									td.attr('class', cell.status)
									var text = s.text(td[0].x.baseVal.value + cellH/2 - 6,td[0].y.baseVal.value+cellH/2 + 8, cell.count);
									text.attr({'font-size':"22", 'font-weight':"bold"});

									td.removeClass('initial');
								}
							}
						})
						if (json.winner.time) {
							$('#winner').html('Победа! Время:' + json.winner.time + ' сек. Ходов: '+json.winner.turn);
							$('#timer').hide();
							checkPhone();
							$('#winner-data').fadeIn();

							clearInterval(timerId);
						}
						console.log(json.winner);
						//$('#test').html(JSON.stringify(json.test))
					})



				break;
		}
	});


	Snap.load("/images/miner.svg", function (f) {
	 var g = f.select("g");

	 f.selectAll("polygon[fill='#09B39C']").attr({
	 fill: "#fc0"
	 })


	 //s.append(g);
	 //g.drag();
	 });

})

