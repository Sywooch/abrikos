<?php
$command = Yii::$app->db->createCommand('select * from flood');
$flood = $command->queryOne();
?>
<form onsubmit="return setFlood(this)">
	Interval: <input name="interval" value="<?=$flood['interval']?>"/>
	Duration:<input name="duration" value="<?=$flood['duration']?>"/>
	<?=$flood['date']?>
	<input type="submit" />
	<div id="flood-debug"></div>
</form>
<hr/>
<script>
	function setFlood(form) {
		$.post('/site/flood',$(form).serialize(),function (data) {
			$('#flood-debug').html(data);
		})
		return false;
	}
</script>
