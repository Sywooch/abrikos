<div class="quest-image-wrapper bg-color-{% var d=o.quest.id-Math.floor(o.quest.id/10)*10; print(d); %}">
	<img src="{%=o.quest.image%}"  class="quest-image" alt="quest image"/>
</div>
<h3 class="quest-name">{%=o.quest.name%}</h3>
<h4 id="answer-choose-header">Выберите {% if (o.quest.multi==1000000) { %}несколько ответов{% }else{ %}ответ{% } %}:</h4>
<div id="quest-alert" class="alert alert-danger collapse"></div>
<form onsubmit="return questSubmit({%=o.quest.id%},this)">
	<input name="email" type="hidden" id="pretendent-form-email"/>
<div class="answer-section">
	{% for (var i=0; i<o.answers.length; i++) { %}

		<label class="answer-wrapper" id="answer-item-{%=o.answers[i].id%}">
			{% include('answer-view', {'answer':o.answers[i],'multi':o.quest.multi}); %}
		</label>
	{% } %}
	<div class="spacer"></div>
</div>
	<div id="quest-description"></div>
	<input type="submit" value="Ответить" class="btn btn-primary" id="btn-quest-submit"/>
	<input type="submit" value="К следующему вопросу" class="btn btn-info collapse" id="btn-quest-next" />
</form>


