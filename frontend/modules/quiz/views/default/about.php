<?php
use frontend\modules\quiz\models\Quiz;
use yii\bootstrap\Html;

?>
<div class="module-about clearfix">
	<p>Знаете какие-нибудь не очевидные факты?</p>
	<p>Хотите узнать насколько они известны другим людям</p>
	<p><?= Html::a('Проведите викторину!', '/quiz/create',['data'=>['method'=>'post'], 'class'=>'btn btn-primary'])?></p>
	<p>Задайте вопрос и укажите один или несколько верных ответов.</p>
		<p>Предложите Вашим друзьям и знакомым показать свои знания!</p>
<?=Html::a('Начать!', '/quiz/create',['class'=>'btn btn-success','data'=>['method'=>'post']])?>

<h3>Опубликованные викторины</h3>
<div style="width: 70%; margin: auto">
<?php
$quizs = Quiz::find()->where(['publish'=>1])->orderBy('date desc')->all();
foreach ($quizs as $quiz) :?>
	<div class="about-item thumbnail col-lg-6">
	<span class="thumbnail"><?=Html::img($quiz->cover)?></span>
	<h4><?=Html::a($quiz->name,$quiz->link)?></h4>
		<small><?=Html::a($quiz->description,$quiz->link)?></small>
	</div>
<?php endforeach;?>
</div>
</div>