<?php
$this->title = 'Восстановление доступа к сервису "'.$this->context->module->description.'"';
$this->params['breadcrumbs'][] = ['label' => 'Мои сервисы "'.$this->context->module->description.'"' , 'url' => ['list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?=$this->title?></h1>
<form method="post" action="/<?=$this->context->module->name?>/restore/start">
	<input name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->csrfToken?>" type="hidden"/>
	Укажите код сервиса к которму требуется восстановить доступ
	<input name="code" value="<?=$id?>">
	<input type="submit" value="Восстановить доступ">
</form>
