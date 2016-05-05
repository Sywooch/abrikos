<?php
\frontend\modules\quiz\QuizProcessAsset::register($this);
$this->title = \yii\bootstrap\Html::encode($model->name)
?>
<div class="view-container">
	<h1>Викторина: "<?= \yii\bootstrap\Html::encode($model->name)?>" <small><?= \yii\bootstrap\Html::encode($model->description)?></small></h1>
	<a href="/quiz/create" class="btn btn-primary" data-method="post">Создать свою викторину</a>
	<?=$this->render('@app/views/layouts/facebook-share')?>
	<div>
	Ежемесячно проводится конкурс викторин. Победитель определяется по рейтингу активности  этой страницеы на Facebook. Текущий рейтинг:
	<?=$model->rating?> (поделиться + нравится + комментарии, обновление 1 раз в час)
	</div>

	Ваш E-mail: <input name="email" placeholder="Адрес или любой идентификатор" id="pretendent-email"/>
	<div class="quest-wrapper">
		<div id="quest-data" class="quest-data"></div>
		<div id="result-data"></div>
	</div>
	<div class="spacer"></div>

	<script type="text/x-tmpl" id="quest-view">
	<?=$this->render('quest-view')?>
	</script>
	<script type="text/x-tmpl" id="answer-view">
	<?=$this->render('answer-view')?>
	</script>
	<script>
		$(function () {
			questView();
		})
	</script>
	<input id="quiz-id" value="<?=$model->id?>" type="hidden" />
</div>