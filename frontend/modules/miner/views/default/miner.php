<?php \app\modules\miner\MinerAsset::register($this)?>

<?php $this->title = 'Сапер  '?>
<span id="timer" ></span>


<table id="miner"></table>
<a href="javascript:void(0)" onclick="fillTable(9,9,12)" class="btn btn-primary">Новая игра</a>
<script>
	$(function(){ fillTable(9,9,12)})
</script>

<div id="winner" style="color:red"></div>


<div id="winner-data" class="alert alert-success">
	<h3>Поздравляем с победой! </h3>
	<!--form onsubmit="return winnerRegister(this)">
		Номер мобильного телефона для выплаты приза (10 цифр начиная с 9) <input name="phone" placeholder="**********" onkeyup="checkPhone()" id="winner-phone"/>
		<input type="submit" value="Отправить" class="collapse" id="winner-submit">
	</form-->
</div>
<?php //$this->registerJsFile('/js/minerGame.js', ['position' => \yii\web\View::POS_HEAD, 'depends' => 'yii\web\YiiAsset']);?>
