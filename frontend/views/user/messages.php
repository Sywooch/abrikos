<?php
$dataProvider = new \yii\data\ActiveDataProvider([
	'query' => \common\models\Message::find()->where(['user'=>Yii::$app->user->id]),
	'pagination' => [
		'pageSize' => 5,
	],
]);

\yii\widgets\Pjax::begin();
echo \yii\widgets\ListView::widget([
	'dataProvider' => $dataProvider,
	'itemOptions' => ['class' => 'item'],
	'itemView' => '/message/_view',
	//'pager' => ['class' => \kop\y2sp\ScrollPager::className()]
]);
\yii\widgets\Pjax::end();
