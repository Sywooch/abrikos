<?php $this->title = 'Быки и коровы. Результат '.$model->player?>
<?php
$text = '<strong>Игра от '. $model->date .'</strong>.
Затраченно <big>'.$model->steps. '</big> ходов и '.$model->time.' секунд на угадывание числа.
'
?>
<div class="well"><?=$text?></div>
<div><a href="/game/cows">Играть в "Быки и коровы"</a></div>
<?php $this->registerMetaTag(['property'=>'og:description',  'content' => strip_tags($text) ]);?>
<?php  
//$tour = \common\models\Tour::findOne(['finished'=>0]);
//$likeThat = $model->userObject->getFightLink($tour->id);
$likeThat = \Yii::$app->request->getAbsoluteUrl();
//$likeThat = \yii\helpers\Url::base('http').\yii\helpers\Url::toRoute(['user/view', 'id'=>$model->user])
?>
<div class="fb-like" data-href="<?=$likeThat?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>        
