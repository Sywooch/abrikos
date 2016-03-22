<h3>Таблица недели</h3>
Победитель определяется по минимальному времени. Приз в 100 рублей отправляю на мобильный телефон.
<table class="table table-condensed">
	<tr>
		<th>Телефон</th><th>Дата</th><th>Время</th>
	</tr>
<?php
$start =  date('Y-m-d', strtotime('last Sunday'));
$phones = \common\models\Phones::find()->where("date>'$start'")->orderBy('rate')->all();
foreach($phones as $phone){
	print "<tr><td>$phone->phone2</td><td>$phone->date</td><td>$phone->rate</td></tr>";
}
?>
</table>
