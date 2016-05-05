<div class="quest-wrapper" data="{%=o.quest.id%}" id="quest-{%=o.quest.id%}">
	<a name="quest-{%=o.quest.id%}"></a>
	<form id="quest-form-{%=o.quest.id%}" onchange="questUpdate({%=o.quest.id%})">
	<div class="quest-tools">
		<a href="javascript:answerAdd({%=o.quest.id%})" class="btn btn-xs btn-success">Добавить ответ</a>
		<a href="javascript:showMediaDialog({%=o.quest.id%},'quest')" class="btn btn-xs btn-primary">фото/видео</a>
		<a href="javascript:questSort({%=o.quest.id%}, 1)" class="btn btn-xs btn-info glyphicon glyphicon-arrow-up" title="Поднять"></a>
		<a href="javascript:questSort({%=o.quest.id%}, 0)" class="btn btn-xs btn-info glyphicon glyphicon-arrow-down" title="Опустить"></a>
		<!--span title="Отвечающий может выбрать несколько вариантов ответа"><input type="checkbox" value="1" name="multi" {% if (o.quest.multi) { %}checked{% } %} > Множественный выбор</span-->
	</div>
	<div class="quest-inputs bg-color-{% var d=o.quest.id-Math.floor(o.quest.id/10)*10; print(d); %}" >
		<div class="quest-image" onclick="showMediaDialog({%=o.quest.id%},'quest')">
			<img src="{%=o.quest.image%}" id="quest-image-{%=o.quest.id%}" class="quest-image-object" style="display: none" onload="this.style.display=''"/>
			<!--iframe class="embed-responsive-item" src="" frameborder="0" allowfullscreen></iframe-->
		</div>

			<div class="quest-section">
				<textarea name="name" placeholder="Введите текст вопроса" class="quest-textarea-name" data="{%=o.quest.id%}">{%=o.quest.name%}</textarea>
				<textarea name="description" placeholder="Введите обоснование ответа. Не обязательно">{%=o.quest.description%}</textarea>
			</div>

		<a data-confirm="Удалить вопрос?" href="javascript:questDelete({%=o.quest.id%})" class="btn btn-danger btn-xs ">Удалить</a>
	</div>
	</form>
	<div class="answer-section" id="answer-quest-{%=o.quest.id%}">
		{% for (var i=0; i<o.answers.length; i++) { %}
		{% include('tmpl-answer', o.answers[i]); %}
		{% } %}
	</div>
	<hr class="spacer"/>
	<div class="answer-help">
		<span class="glyphicon glyphicon-check"></span>Правильный ответ
		<span class="glyphicon glyphicon-picture"></span>Загрузить картинку<br/>
		<span class="glyphicon glyphicon-minus"></span>Удалить картинку
		<span class="glyphicon glyphicon-trash"></span>Удалить ответ

	</div>

	<hr class="spacer"/>
</div>

<!-- http://blueimp.github.io/JavaScript-Templates/ -->