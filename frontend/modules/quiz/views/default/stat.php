<?php

//$stats = \frontend\modules\quiz\models\Stat::find()->where(['tour'=>date('Ym')])->groupBy('quiz')->all();
use yii\helpers\Json;

$command = Yii::$app->db->createCommand('SELECT avg(share+comment) cnt, name, cast(quiz_stat.date as date) date, quiz FROM `quiz_stat` INNER JOIN quiz_quiz on quiz_stat.quiz = quiz_quiz.id WHERE tour='.date('Ym').' and quiz_quiz.publish>0 group by day(quiz_stat.date), quiz');
$stats = $command->queryAll();
foreach ($stats as $stat) {
	$series[$stat['name']][] = $stat['cnt'];
	$xAxis[] = $stat['date'];
}
foreach ($series as $k=>$sery) {
	$s[]=['name'=>$k,'data'=>$sery];
}
?>

<script src="http://code.highcharts.com/highcharts.js"></script>
<div id="container" style="width:100%; height:400px;"></div>
<script>
	$(function () {
		$('#container').highcharts({
			chart: {
				type: 'line'
			},
			title: {
				text: 'Социальный рейтинг викторин (лайк + поделиться + коммент)'
			},
			xAxis: {
				categories: <?=Json::encode($xAxis);?>
			},
			yAxis: {
				title: {
					text: 'Лайки, комменты'
				}
			},
			series:<?= Json::encode($s, JSON_NUMERIC_CHECK);?>
		});
	});
</script>

