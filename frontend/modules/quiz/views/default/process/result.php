
<?php
use yii\bootstrap\Html;
\frontend\modules\quiz\QuizProcessAsset::register($this);
$right = 0;
$count = 0;
foreach ($results as $result):?>
<div class="quest-wrapper  bg-color-<?=$result->quest % 10?>">
	<div class="quest-data">
	<h3 class="quest-name"><?=Html::encode($result->quest0->name)?></h3>
	<div class="quest-image-wrapper">
		<?=Html::img($result->quest0->image,['class'=>'image-quest'])?>
	</div>
	<div class="answer-section">
		<h4>Ваш ответ:</h4>
		<label class="answer-wrapper" id="answer-item-{%=o.answers[i].id%}">
			<div class="answer-data-wrapper">
				<?php if($result->answer0->media):?>
					<div class="thumbnail img-thumb"><?=Html::img($result->answer0->image,['class'=>'result-image-answer'])?></div>
				<?php else:?>
					<div class="thumbnail txt-thumb"><?=Html::encode($result->answer0->text)?></div>
				<?php endif?>
			</div>
		</label>
		<div class="clearfix"></div>
		<?php	if($result->quest0->quiz0->result_correct_inform):?>
			<div class="result-answer-status result-answer-correct-<?=$result->isRight?>"><?=$result->isRight?'Верно. ':'Не верно. '?></div>
		<?php endif?>
		<?php	if($result->quest0->quiz0->result_correct_show):?>
		<h4 class="answer-choose-header">Верные ответы:</h4>
			<?php foreach ($result->quest0->rightAnswers as $rightAnswer):?>
				<label class="answer-wrapper" id="answer-item-{%=o.answers[i].id%}">
					<div class="answer-data-wrapper">
						<?php if($rightAnswer->media):?>
						<div class="thumbnail img-thumb"><?=Html::img($rightAnswer->image,['class'=>'result-image-answer'])?></div>
						<?php else:?>
						<div class="thumbnail txt-thumb"><?=Html::encode($rightAnswer->text)?></div>
						<?php endif?>
					</div>
				</label>
			<?php endforeach;?>
		<div class="clearfix"></div>

		<?php if($result->quest0->description):?>
		<h4>Комментарий к ответу</h4>
		<i>
			<div id="quest-description"><?=Html::encode($result->quest0->description);?></div>
		</i>
		<?php endif;?>
	</div>
	<?php endif	?>
	</div>
</div>
<?php endforeach;?>
