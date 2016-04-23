<?php
$this->title = 'Опрос №'.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Мои опросы', 'url' => ['list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<h3><?=$model->text?></h3>
<form onsubmit="return questionResult<?=$model->id?>(this)">
	<ol>
		<?php
		foreach($model->answers as $a){
			print '<li><input type="'. ($model->multiselect ? 'checkbox':'radio') .'" name="answer[]" value="' . $a->id . '"/> ' . $a->text . '</li>';
		}
		?>
	</ol>
	<?php if($model->active):?>
		<input type="submit" value="Голосовать">
	<?php endif?>
</form>
<a href="javascript:void(0)" onclick="questionResult<?=$model->id?>()">результаты</a>
<div style="font-size: .7em">Создай свой опрос на сайте "<?=\yii\helpers\Html::a( \yii\helpers\Html::encode(Yii::$app->name),'http://' . $_SERVER['SERVER_NAME'] . '/poll',['target'=>'_blank'])?>"</div>
<div id="question-result"></div>

<script>

	function questionResult<?=$model->id?>(form){

		$.ajax({
			url:'http://<?=$_SERVER['SERVER_NAME']?>/poll/result/<?=$model->id?>',
			data:$(form).serialize(),
			dataType: 'jsonp', // Notice! JSONP <-- P (lowercase)
			success:function(json){
				$('#question-result').html(json.html);
				$("#question-result" ).dialog({
					title:'Опрос',
					resizable: false,
					height:600,
					width:600,
					modal: true,
					buttons: {
						'Закрыть': function() {
							$( this ).dialog( "close" );
						},
						'Создай свой опрос':function(){
							document.location.href='http://<?=$_SERVER['SERVER_NAME']?>/poll';
						}
					}
				});

			},
			error:function(a,b,c){
				console.log(a,b,c);
			}
		});

		return false
	}

</script>