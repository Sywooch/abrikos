<input id="tour-id" value="{%=o.id%}" type="hidden"/>
<div class="row" id="images-wrap">
	{% for (var i=0; i<o.images.length; i++) { %}
		<div class="col-lg-3">
				<span class="thumbnail">
				<img src="{%=o.images[i].src%}" />
				</span>
		</div>
	{% } %}
</div>
<div id="answer-wrap">
	<div id="answer-container"  class="clearfix" >
		{% for (var i=0; i<o.answer; i++) { %}
			<div class="letter-container right-letters-container" onclick="letterReturn(this)"></div>
		{% } %}
	</div>

	<div id="letters-container" class="clearfix" >

		{% for (var i=0; i<JSON.parse(o.shuffle).length; i++) { %}
			<div class="letter-container" id="letter-container-{%=i%}" onclick="chooseLetter({%=i%})">
				<span class="btn btn-danger" id="letter-{%=i%}" data-id="{%=i%}">{%=JSON.parse(o.shuffle)[i]%}</span>
			</div>
		{% } %}
	</div>
</div>
<div id="answer-ok" class="alert alert-success collapse answer-result">
	Верно!!!
	<button class="btn btn-success" onclick="getTourData()" id="btn-tour-next">Следующий вопрос</button>
</div>
<div id="answer-wrong" class="alert alert-danger collapse answer-result">
	Не верно.
	<button class="btn btn-warning" onclick="clearAnswer()" id="btn-tour-next">Отчистить ответ</button>
</div>