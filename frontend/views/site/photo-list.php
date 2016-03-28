<div>
	<?php
	use yii\data\ActiveDataProvider;
	use yii\widgets\ListView;

	$dP = new ActiveDataProvider([
		'query' => \common\models\Photo::find()->orderBy('date desc'),
		'pagination' => [
			'pageSize' => 10,
		],
	]);
	echo ListView::widget( [
		'dataProvider' => $dP,
		'itemView' => '_photo',
	] );
	?>
</div>