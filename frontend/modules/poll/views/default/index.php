<?php
use yii\bootstrap\Html;

$this->title = 'Сервис создания опросов.';
$this->params['breadcrumbs'][] = $this->title;

?>
<h1><?= Html::encode($this->title) ?></h1>


<?=$this->render('about')?>

<?=Yii::$app->user->isGuest ? '' : \yii\bootstrap\Html::a('Мои опросы','/poll/list',['class'=>['btn','btn-primary']])?>