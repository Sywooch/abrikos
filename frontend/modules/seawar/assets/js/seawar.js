var SHIPS = [0,4,3,2,1];
var TABLE_SIZE = 10;

$(function () {
	$('.cell').click(function (event) {
		$('#alert').hide();
		var cell = $(this);
		if(cell.hasClass('ship')){
			rotateShip(cell);
		}else {
			for (var i = 1; i < SHIPS.length; i++) {
				var count = $('td[shipSize=' + i + ']').length / i;
				if (count < SHIPS[i]) {
					var result = setShip(cell, i);
					if (!result.result) {
						$('#alert').fadeIn().text(result.message);
					}
					return;
				}
			}
		}
	})

})

function rotateShip(cell) {
	var cells = $("td[shipId='"+cell.attr('shipId')+"']");
	var start = $(cells[0]);
	var end = $(cells[cell.length-1]);
	var Xs = cell.attr('x') - cell.attr('y') + start.attr('y');
	var Ys = cell.attr('y') - cell.attr('x') + start.attr('x');
	var Xe = cell.attr('x') + end.attr('y') - cell.attr('y');
	var Ye = cell.attr('y') - end.attr('x') + cell.attr('x');

	console.log(Xs,Ys);
	console.log(Xe,Ye);
}

function fillRandom() {
	
}

function setShip(cell, size){
	var nbrs, test, x, y;
	var orient = 'h';
	var X = cell.attr('x')*1;
	var Y= cell.attr('y')*1;
	var cansetX = true;
	var cansetY = true;

	for(x=X+1;x<X+size;x++){
		test = $('#cell-'+x+'-'+Y);
		nbrs = getNeighbours(test);
		if(nbrs.length>0 || test.hasClass('ship')){
			cansetX = false;
			break;
		}
	}
	if(x>TABLE_SIZE+1) cansetX = false;

	for(y=Y+1;y<Y+size;y++){
		test = $('#cell-'+X+'-'+y);
		nbrs = getNeighbours(test);
		if(nbrs.length>0 || test.hasClass('ship')){
			cansetY = false;
			break;
		}
	}
	if(y>TABLE_SIZE+1) cansetY = false;


	if(cansetX){
		for(x=X;x<X+size;x++){
			$('#cell-'+x+'-'+Y).addClass('ship').attr('shipId',X+'-'+Y).attr('shipSize',size);
		}
		return {result:true,message:'Установлен '+size+'-палубный X'};
	}
	if(cansetY){
		for(y=Y;y<Y+size;y++){
			$('#cell-'+X+'-'+y).addClass('ship').attr('shipId',X+'-'+Y).attr('shipSize',size);
		}
		return {result:true,message:'Установлен '+size+'-палубный Y'};
	}
	return {result:false,message:'Невозможно установить '+size+'-палубный в эту клетку'};
}


function getNeighbours(cell) {
	var X = cell.attr('x')*1;
	var Y= cell.attr('y')*1;
	nb = [];
	for(var i=X-1; i<X+2;i++){
		for(var j=Y-1; j<Y+2;j++){
			if(!(i==X && j==Y)){
				var testCell = $('#cell-'+i+'-'+j);
				if(testCell.hasClass('ship')){
					nb.push(testCell);
				}
			}
		}
	}
	return nb;
}