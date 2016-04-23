<div class="quest-wrapper" data="{%=o.quest.id%}" id="quest-{%=o.quest.id%}">
	<a name="quest-{%=o.quest.id%}"></a>
	<div class="quest-tools">
		<a href="javascript:answerAdd({%=o.quest.id%})" class="btn btn-xs btn-success">Добавить ответ</a>
		<a href="javascript:showMediaDialog({%=o.quest.id%},'quest')" class="btn btn-xs btn-primary">фото/видео</a>
		<a href="javascript:questSort({%=o.quest.id%}, 1)" class="btn btn-xs btn-info glyphicon glyphicon-arrow-up" title="Поднять"></a>
		<a href="javascript:questSort({%=o.quest.id%}, 0)" class="btn btn-xs btn-info glyphicon glyphicon-arrow-down" title="Опустить"></a>
		<?=\yii\bootstrap\Html::a('Удалить', 'javascript:questDelete({%=o.quest.id%})' ,['class'=>'btn btn-danger btn-xs ', 'data'=>['confirm'=>'Удалить вопрос?']])?>
	</div>
	<div class="quest-inputs bg-color-{% var d=o.quest.id-Math.floor(o.quest.id/10)*10; print(d); %}" >
		<div class="quest-image" onclick="showMediaDialog({%=o.quest.id%},'quest')">
			<img src="{%=o.questImage%}" id="quest-image-{%=o.quest.id%}" class="quest-image-object" style="display: none" onload="this.style.display=''"/>
			<!--iframe class="embed-responsive-item" src="" frameborder="0" allowfullscreen></iframe-->
		</div>
		<form id="quest-form-{%=o.quest.id%}" onchange="questUpdate({%=o.quest.id%})">
			<div class="quest-section">
				<textarea name="name" placeholder="Введите текст вопроса">{%=o.quest.name%}</textarea>
			</div>
		</form>
	</div>
	<div class="answer-section" id="answer-quest-{%=o.quest.id%}">
		{% for (var i=0; i<o.answers.length; i++) { %}
		{% include('tmpl-answer', o.answers[i]); %}
		{% } %}
	</div>
	<hr class="spacer"/>
</div>

<!-- http://blueimp.github.io/JavaScript-Templates/ -->