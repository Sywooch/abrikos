<div class="tour-wrap" id="tour-wrap-{%=o.tour.id%}" data-id="{%=o.tour.id%}">
	<a href="/<?=$this->context->module->id?>/{%=o.tour.quest%}?tour={%=o.tour.id%}" class="btn btn-info" target="_blank" title="Открыть просмотр"> Задание #{%=o.tour.id%}</a>
	<button class="glyphicon glyphicon-arrow-left btn" onclick="tourPager(-1)" title="Предыдущее задание"></button>
	<button class="glyphicon glyphicon-arrow-right btn" onclick="tourPager(1)" title="Следующее задание"></button>
	<button class="btn btn-primary" onclick="tourAdd()">Добавить задание</button>
	<div class="hidden" id="tour-data" data-id="{%=o.tour.id%}"></div>
	<form onchange="tourUpdate(this,{%=o.tour.id%})">
		<div class="form-group-sm">
			Ответ:<input name="Tour[answer]" value="{%=o.tour.answer%}" onchange="$('#input-word-search').val(this.value)" class="form-control"/>
		</div>
		<div class="form-group-sm">
			<input name="Tour[eng]" value="0" type="radio"  {% if(o.tour.eng==0) { %}checked{% }%} /> Добавочные русскими
			<input name="Tour[eng]" value="1" type="radio"  {% if(o.tour.eng==1) { %}checked{% }%} /> Добавочные латинскими

		</div>
		<div class="form-group-sm">
			Добавочных букв
			{% for (var i=5; i<11; i++) { %}
			<input name="Tour[letters_count]" value="{%=i%}" type="radio"  {% if (i == o.tour.letters_count) { %}checked{% }%} />{%=i%}
			{% } %}
		</div>
		<div id="form-letters-{%=o.tour.id%}" class="alert alert-success">{%=o.tour.shuffle%}</div>
		<div id="form-image-inputs-{%=o.tour.id%}"></div>
		<div id="tour-error-{%=o.tour.id%}" class="alert alert-danger collapse"></div>
	</form>
	<form onchange="tourUpdate(this,{%=o.tour.id%})">

		<input name="Tour[enabled]" value="1" type="radio"  {% if(o.tour.enabled == 1) { %}checked{% }%} /> Доступно для ответов
		<input name="Tour[enabled]" value="0" type="radio"  {% if(o.tour.enabled == 0) { %}checked{% }%} /> Не доступно для ответов

	</form>
	<div class="well" id="dragandrophandler">
		Перетащите сюда 4 фото для загрузки
		<progress class="collapse" id="drag-progress"></progress>
	</div>
	<div id="debug"></div>
	<div id="images-table" class="row">
		{% for (var i=0; i<o.images.length; i++) { %}
		{% include('tmpl-choosen-image', o.images[i]); %}
		{% } %}
	</div>
	<div class="help-block">Клик по картинке для загрузки изображения.</div>
	<button onclick="imageChoosenDownload({%=o.tour.id%})" class="btn btn-primary collapse" id="button-choosen-download">Сохранить картинки</button>

	<textarea id="shuffle-{%=o.tour.id%}" class="hidden">{%=o.tour.shuffle%}</textarea>

</div>



