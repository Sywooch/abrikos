<?php
use frontend\modules\quiz\models\Quiz;
use yii\bootstrap\Html;

?>
<div class="module-about">
Знаете какие-нибудь не очевидные факты? Хотите узнать насколько они известны другим людям?
<?= Html::a('Проведите викторину!', '/quiz/create',['data'=>['method'=>'post']])?>
	Задайте вопрос и укажите один или несколько верных ответов. Предложите Вашим друзьям и знакомым показать свои знания!
<?=Html::a('Начать!', '/quiz/create',['class'=>'btn btn-success btn-xs','data'=>['method'=>'post']])?>.

<h3>Опубликованные викторины</h3>
<?php
$quizs = Quiz::find()->where(['publish'=>1])->orderBy('date desc')->all();
foreach ($quizs as $quiz) :?>
	<div class="about-item thumbnail">
	<span class="thumbnail2"><?=Html::img($quiz->cover)?></span>
	<h4><?=Html::a($quiz->name,$quiz->link)?></h4>
		<small><?=Html::a($quiz->description,$quiz->link)?></small>
	</div>
<?php endforeach;?>

</div>