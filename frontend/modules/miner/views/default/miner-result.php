<?php $this->title = 'Сапер. Результат '.$model->player?>
<?php
$text = 'Игра от <strong>'. $model->date .'</strong>.
Поле/бомб: <b>'.$model->param.'</b>
Затраченно <b>'.$model->turn. '</b> ходов и <b>'.$model->time.'</b> секунд на разминирование.
Рейтинг: '.$model->rate .' ';
?>
<div class="well"><?=$text?></div>
<div><a href="/game/cows">Играть в "Сапер"</a></div>
<?php $this->registerMetaTag(['property'=>'og:description',  'content' => strip_tags($text) ]);?>
<?php
//$tour = \common\models\Tour::findOne(['finished'=>0]);
//$likeThat = $model->userObject->getFightLink($tour->id);
$likeThat = \Yii::$app->request->getAbsoluteUrl();
//$likeThat = \yii\helpers\Url::base('http').\yii\helpers\Url::toRoute(['user/view', 'id'=>$model->user])
?>
<div class="fb-like" data-href="<?=$likeThat?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>        
