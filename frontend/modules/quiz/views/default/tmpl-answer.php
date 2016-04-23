<div class="answer-wrapper" id="answer-{%=o.id%}">
	<div class="answer-tools">
		<a href="javascript:showMediaDialog({%=o.id%},'answer')" class="glyphicon glyphicon-picture" title="Загрузить картинку"></a>
		<a data-confirm="Удалить ответ?" href="javascript:answerDelete({%=o.id%})" class="glyphicon glyphicon-trash" title="Удалить ответ"></a>
	</div>
	<form id="answer-form-{%=o.id%}" onchange="answerUpdate({%=o.id%})">
		<textarea name="text" placeholder="Введите текст ответа">{%=o.text%}</textarea>
		<img src="/uploads/quiz/answer/{%=o.id%}.jpeg" id="answer-image-{%=o.id%}" class="answer-image-object" style="display: none" onload="this.style.display=''"/>
	</form>
</div>