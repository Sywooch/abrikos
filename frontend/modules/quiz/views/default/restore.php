<?php
$this->title = 'Восстановление доступа к викторине';
$this->params['breadcrumbs'][] = ['label' => 'Мои викторины', 'url' => ['list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?=$this->title?></h1>
<form method="post" action="/quiz/restore-start">
	<input name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->csrfToken?>" type="hidden"/>
	Укажите код викторины к которой требуется восстановить доступ
	<input name="code" value="<?=$id?>">
	<input type="submit" value="Восстановить доступ">
</form>
