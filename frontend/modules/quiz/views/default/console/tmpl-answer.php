<div class="answer-wrapper {% if (o.correct) { %}answer-correct{% }%}" id="answer-{%=o.id%}">
	<div class="answer-tools">
		<a href="javascript:void(0)" onclick="answerCorrect({%=o.id%},this)" class="glyphicon glyphicon-{% if (o.correct) { %}check{% } else { %}unchecked{% } %}" title="Правильный ответ"></a>
		<a href="javascript:showMediaDialog({%=o.id%},'answer')" class="glyphicon glyphicon-picture" id="answer-text-button-{%=o.id%}" title="Загрузить картинку"></a>
		<a href="javascript:pictureHide({%=o.id%})" class="glyphicon glyphicon-minus {% if (!o.media) { %}collapse{% } %}" title="Отменить картинку" id="answer-image-button-{%=o.id%}"></a>
		<a data-confirm="Удалить ответ?" href="javascript:answerDelete({%=o.id%})" class="glyphicon glyphicon-trash" title="Удалить ответ"></a>
	</div>
	<div class="answer-inputs">
	<textarea name="text" placeholder="Введите текст ответа" onchange="answerUpdate({%=o.id%},this)" id="answer-text-{%=o.id%}" class="{% if (o.media) { %}collapse{% } %}">{%=o.text%}</textarea>
	<img src="{% if (o.media) { %} {%=o.image%}{% } else { %}#{% } %}" id="answer-image-{%=o.id%}" class="answer-image-object {% if (!o.media) { %}collapse{% } %}" />
	</div>
</div>