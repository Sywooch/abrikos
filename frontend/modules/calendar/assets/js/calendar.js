/**
 * Created by abrikos on 02.04.16.
 */
function retroDate(year, day) {
	$.get('/calendar/get-retro',{year:year, day:day},function (data){
		alert(data)
	} )
}