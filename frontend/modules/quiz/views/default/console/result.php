<?php
//\frontend\modules\quiz\QuizConsoleAsset::register($this);
use yii\bootstrap\Html;
$this->title = 'Ответы на викторину #' . $results[0]->quest0->quiz;
$this->params['breadcrumbs'][] = ['label' => 'Мои викторины', 'url' => ['list']];
$this->params['breadcrumbs'][] = ['label' => 'Просмотр #' . $results[0]->quest0->quiz, 'url' => ['view', 'id' => $results[0]->quest0->quiz]];
$this->params['breadcrumbs'][] = ['label' => 'Редактировать #' . $results[0]->quest0->quiz, 'url' => ['update', 'id' => $results[0]->quest0->quiz]];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php

$count = $correct = 0;
foreach ($results as $r){
	$count++;
	$correct += $r->answer0->correct;
}
$stat =  "$correct из $count";
?>
<h1><?=$results[0]->quest0->quiz0->name?></h1>
<dl class="dl-horizontal">
	<dt>Начало ответа:</dt>	<dd><?= $results[0]->date?></dd>
	<dt>Завершение ответа</dt><dd><?=$results[count($results)-1]->date?></dd>
	<dt>Результат ответов</dt><dd><?=$stat?></dd>
</dl>


<div class="nav">
<a href="/quiz/update/<?=$results[0]->quest0->quiz?>" class="btn btn-success" target="_blank">Редактировать</a>
<a href="<?=$results[0]->quest0->quiz0->link?>" class="btn btn-primary" target="_blank">Просмотр</a>
</div>

<?php
$right = 0;
$count = 0;
foreach ($results as $result):?>
	<h3><?=Html::encode($result->quest0->name)?></h3>
	<div class="quest-wrapper bg-color-<?= $result->quest % 10?>">
		<div class="row">
			<div class="col-md-7">
				<div class="quest-image">
				<?=Html::img($result->quest0->image,['class'=>'quest-image-object'])?>
				</div>
			</div>
			<div class="col-md-5">
				<h4>Ответ:</h4>
				<div class="result-user-answer thumbnail" style="height: 200px; overflow: hidden; width: 200px">
					<?=$result->answer0->media ? Html::img($result->answer0->image,['class'=>'result-image-answer', 'style'=>'max-height:100%']) : Html::encode($result->answer0->text)?>
				</div>
				<div class="result-answer-status result-answer-correct-<?=$result->isRight?>"><?=$result->isRight?'Верно. ':'Не верно. '?></div>
				Ответ дан <?=$result->date?>
			</div>
		</div>
	</div>
<?php endforeach;?>

