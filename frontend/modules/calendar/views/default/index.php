<?php
use yii\jui\DatePicker;
\app\modules\calendar\CalendarAsset::register($this);
?>
<?= Yii::t('calendar', 'Select language');?>
<a href="/calendar/language?l=ru" class="language <?=Yii::$app->request->cookies['language']?>-russian">Russian</a>
<a href="/calendar/language?l=en" class="language <?=Yii::$app->request->cookies['language']?>-english">Englis</a>

<h1><?=$this->title = Yii::t('calendar', 'Stable calendar Unix Epoch')?></h1>
<div id="calendar-index">
	<form id="form-converter">
		<?= Yii::t('calendar', 'Enter date in retro format');?>
		<?=DatePicker::widget([
			'name'  => 'retro',
			'value'  => $calendar->retro,
			//'language' => 'ru',
			'dateFormat' => 'yyyy-MM-dd',
			'options'=>[
				'onchange'=>'$("#form-converter").submit()',
			]
		]);?>
		<input type="submit" value="<?= \Yii::t('calendar', 'Goto');?>">
	</form>
	<?= \Yii::t('calendar', 'UNIX epoch date');?>: <strong><?=$calendar->formatDate(2)?></strong> (<?=$calendar->formatDate(1)?>)
	<br/>
	<?= \Yii::t('calendar', 'Year');?>: <?=$calendar->year?>,
	<?= \Yii::t('calendar', 'Month');?>: <?=$calendar->month?> (<?=$calendar->getMonthName()?>),
	<?= \Yii::t('calendar', 'Day');?>: <?=$calendar->day?>
	<h2 class="year">
		<?=$calendar->getYearText() ?>
	</h2>
	<?php $yearDay=0?>
	<?php 	for($month = 1; $month<=13;$month++):?>
		<div class="month">
			<h4 class="title"><?= \Yii::t('calendar', $calendar::MONTH[$month]);?></h4>
			<?php for($day = 1; $day<=7;$day++):?>
				<div class="day week week-<?=$day?>"><?=\Yii::t('calendar', date('D',3600 * 24 * ($day+3))) ?></div>
			<?php endfor?>

			<?php for($day = 1; $day<=28;$day++):?>
				<?php $yearDay++?>
				<div class="day day-<?=($day-1) % 7 + 1?> <?=$yearDay == $calendar->yearDay ? 'this-day':''?>" id="day-<?=$yearDay?>">
					<a href="javascript:retroDate(<?=$calendar->year?>,<?=$yearDay?>)"><?=$day?></a>
				</div>
			<?php endfor?>
		</div>
	<?php endfor?>
		<?php $yearDay++?>
		<div class="month new-year">
			<h4 class="title <?=$yearDay == $calendar->yearDay ? 'this-day':''?>">
				<a href="javascript:retroDate(<?=$calendar->year?>,<?=$yearDay?>)">
					<?= \Yii::t('calendar', 'Year is over day');?>
				</a>
			</h4>
		</div>
	<?php if($calendar->leap):?>
		<?php $yearDay++?>
		<div class="month leap-year">
			<h4 class="title <?=$yearDay == $calendar->yearDay ? 'this-day':''?>">
				<a href="javascript:retroDate(<?=$calendar->year?>,<?=$yearDay?>)">
					<?= \Yii::t('calendar', 'Leap year day');?>
				</a>
			</h4>
		</div>
	<?php endif?>

	<hr style="clear: both"/>
		<?=$this->render('description')?>


</div>
<hr class="clearfix"/>

