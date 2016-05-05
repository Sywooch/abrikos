<?php
use frontend\modules\quiz\models\Result;
\frontend\modules\quiz\QuizProcessAsset::register($this);
$results = Result::findAll(['round'=>$round]);
$count = $correct = 0;
foreach ($results as $r){
	$count++;
	$correct += $r->answer0->correct;
}
$percent = round($correct/$count*100,2);
if ($percent < 10){
	$result = 'Не расстраивайтесь. Попробуйте еще раз';
	$class = 'bg-danger';
}elseif ($percent < 50){
	$result = 'Удовлетворительно';
	$class = 'bg-warning';
}elseif ($percent <80){
	$result = 'Хорошо';
	$class = 'bg-info';
}elseif ($percent<100){
	$result = 'Отлично';
	$class = 'bg-success';
}else{
	$result = 'Идеально';
	$class = 'bg-primary';
}
$result2 = "$correct из $count ($percent%)";

Yii::$app->view->registerMetaTag(['property'=>'og:title', 'content'=>'Результат викторины: ' . $result, 'id'=>'og-title'], 'og-title');
Yii::$app->view->registerMetaTag(['property'=>'og:description', 'content'=>$quiz->name . ' - ' .$quiz->description . '. ' . $result2], 'og-desc');

Yii::$app->view->registerMetaTag(['property' => 'og:image', 'content' => 'http://'.$_SERVER['SERVER_NAME'].$quiz->image],'og-image');
Yii::$app->view->registerMetaTag(['property' => 'og:image:secure_url', 'content' => 'http://'.$_SERVER['SERVER_NAME'].$quiz->image],'og-imagesec');
Yii::$app->view->registerMetaTag(['property' => 'og:image:width', 'content' => 400],'og-image-width');
Yii::$app->view->registerMetaTag(['property' => 'og:image:height', 'content' => 200],'og-image-height');
Yii::$app->view->registerLinkTag(['rel' => 'image_src', 'href' => 'http://'.$_SERVER['SERVER_NAME'].$quiz->image],'og-image-rel');

?>
<h1><?=$this->title = 'Вы завершили викторину'?> "<?=\yii\bootstrap\Html::encode($quiz->name)?>" <small><?=\yii\bootstrap\Html::encode($quiz->description)?></small></h1>
<div id="done-name-desc">
	<img src="<?=$quiz->image?>" id="done-quiz-cover" alt="Обложка викторины"/>
	<h4 id="done-counter" class="<?=$class?>"> Результат: <?=$result?>! <br/><?=$result2?>	</h4>
	<a href="<?=$quiz->link?>" class="btn btn-success">Пройти еще раз</a>
	<a href="/quiz/create" class="btn btn-primary" data-method="post">Создать свою викторину</a>
	<?=$this->render('@app/views/layouts/facebook-share')?>
</div>
<div id="done-results">
<?php
if(in_array($quiz->show_result, [1, 3])){
	echo $this->render('result',['quiz'=>$quiz,'results'=>$results]);
}
?>
</div>
