<table class="table table-condensed">
<tr><th colspan="4"><?=$model->text?></th></tr>
<tr>
	<th>Ответ</th>
	<th colspan="2">Процент</th>
	<th>Кол-во</th>
</tr>
	<?php
	$sum = 0;
	foreach($model->answers as $a){
		$cnt = count($a->votes);
		$votes[$a->id] =['text'=>$a->text , 'count'=>$cnt];
		$sum +=$cnt;
	}

	?>


	<?php
	$width = 200;
	$i=0;
	foreach($votes as $k=>$arr){
		$i++;
		$percent = $arr['count'] / ($sum ? $sum : 1);
		$barWidth = $width * $percent;
		$hash = md5($arr['count']);
		$color = substr($hash, 0, 2).substr($hash, 2, 2).substr($hash, 4, 2);
		$bar = '<div style="width:'.$barWidth.'px; background-color:#'.$color.' ">&nbsp;</div>';
		print '<tr ><td>'.$i.'. '.$arr['text'].'</td><td>'.$bar.'</td><td class="percent">'.round($percent*100,2).'%</td><td style="text-align:right">'.$arr['count'].'</td></tr>';
	}
	?>
</table>