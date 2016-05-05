	<input type="{% if (o.multi==10000000) { %}checkbox{% }else{ %}radio{% } %}" name="answers[]" value="{%=o.answer.id%}">
	<div class="answer-data-wrapper">
	{% if (o.answer.media) { %}
		<div class="thumbnail img-thumb"><img src="{%=o.answer.image%}" alt="answer image" /></div>
	{% } else { %}
		<div class="thumbnail txt-thumb">{%=o.answer.text%}</div>
	{% } %}
	</div>
