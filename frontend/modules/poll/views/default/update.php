<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Question */

$this->title = 'Редактирование опроса #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Мои опросы', 'url' => ['list']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="question-update">
	<h1><?= Html::encode($this->title) ?><small>все изменения сохраняются автоматически</small></h1>
	<?php yii\widgets\Pjax::begin() ?>
	<div class="question-form">

		<?php $form = ActiveForm::begin(['id'=>'question-form', 'options' => ['data-pjax' => true, 'onchange'=>'if (typeof formSubmit == "function") { formSubmit(this);}']]); ?>

		<?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

		<?= $form->field($model, 'active')->checkbox()->hint('Если не разрешено, то посетители смогут только смотреть результаты') ?>
		<?= $form->field($model, 'multiselect')->checkbox()->hint('Если не разрешено, то посетители смогут только смотреть результаты') ?>

		<?php if($model->isNewRecord):?>
			<div class="form-group">
				<?= Html::submitButton('Создать', ['class' => 'btn btn-success' ]) ?>
			</div>
		<?php endif;?>

		<?php ActiveForm::end(); ?>

	</div>
	<?php \yii\widgets\Pjax::end(); ?>
</div>

<h3>Ответы <small>Перетащить для сортировки</small></h3>
<style>
	.answer-textarea{width:100%}
</style>

<table  class="table table-condensed">
	<tr>
		<td></td>
		<td><textarea id="new-answer" onchange="answerCreate(this.value)" class="answer-textarea" placeholder="Добавьте новый ответ"></textarea></td>
		<td><a href="javascript:answerCreate($('#new-answer').val())" tabindex="0" class="btn btn-primary">Добавить</a></td>
	</tr>
	<tbody id="answer-list"></tbody>
</table>
<script>
	$(function(){
		answerList();
	})

	function formSubmit(form){
		$.post('/poll/update/<?=$model->id?>',$(form).serialize(),function(data){console.log(data);});
	}

	function answerUpdate(id,value){
		$.post('/poll/answer-update/'+id,{text:value, question:<?=$model->id?>},function(id){
			console.log(value);
		})

	}

	function answerCreate(value){
		$.ajax({
			url:'/poll/answer-add',
			data:{text:value, question:<?=$model->id?>},
			dataType:'json',
			type:'post',
			success:function(json){
				$('#new-answer').val('');

				drawAnswer($('.answer-input').length+1,json);
			}
		})
	}

	function drawAnswer(tab,item) {
		var row = '<tr class="answer-input" id="answer_'+item.id+'" data="'+item.id+'">';
		//row += '<span class="glyphicon glyphicon-arrow-up"></span><span class="glyphicon glyphicon-arrow-down"></span>';
		row += '<td>'+tab+'</td>';
		row += '<td><textarea  onchange="answerUpdate('+item.id+',this.value)" tabindex="'+tab+'" class="answer-textarea">'+item.text+'</textarea></td>';
		//row += '<td><a href="javascript:answerSort(1,<?=$model->id?>)" class="btn btn-info">Поднять&nbsp;</a><br/><a href="javascript:answerSort(-1,<?=$model->id?>)" class="btn btn-success">Опустить</a>'+item.order+' </td>';
		row += '<td><a href="javascript:void(0)" onclick="answerDelete('+item.id+')" class="btn btn-danger">Удалить</a></td>';
		row += '</tr>';
		$('#answer-list').append(row);
	}

	function answerList(){
		$('#answer-list').html('');
		$.getJSON('/poll/answer-list/<?=$model->id?>',null,function(json){
			var tab = 0;
			$.each(json,function(i, item){
				tab = i+1;
				drawAnswer(tab,item);
			});
			$('#answer-list').sortable({update:function(){
				var orderArray = [];
				$.each($('.answer-input'),function (order,obj) {
					var element = {};
					element.id = $(obj).attr('data');
					element.order = order;
					orderArray.push(element);
				});
				$.post('/poll/sort/<?=$model->id?>',{sort:orderArray});
				//console.log(orderArray);
			}});
		});
	}

	function answerSort(direction,id) {
		$.get('/poll/answer-sort/'+id,{d:direction},function () {
			answerList();
		});
	}

	function answerDelete(id){
		if(confirm('Удалить ответ?')){
			$.post('/poll/answer-delete/'+id , null, function(){ $('#answer_'+id).fadeOut(); });
		}
	}
</script>

<?= Html::a('Просмотр',['view', 'id'=>$model->id],['class'=>'btn btn-success'])?>
<?= Html::a('Просмотр вне сайта',['view', 'id'=>$model->id,'plain'=>1],['class'=>'btn btn-primary'])?>
<?= Html::a('Удалить', ['delete', 'id' => $model->id], [
	'class' => 'btn btn-danger',
	'data' => [
		'confirm' => 'Удалить этот опрос полностью?',
		'method' => 'post',
	],
]) ?>


<h4>Код для вставки на Ваш сайт</h4>
<div class="well">
<?=Html::encode($model->code)?>
</div>
<h4>Ссылка для соц.сетей</h4>
<div class="well">
	<?=$model->link?>
</div>

<div class="collapse"><?= yii\jui\DatePicker::widget(['name' => 'attributeName']) ?></div>
