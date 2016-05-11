<h3>Поиск образа: "{%=o.word%}"</h3>
<div class="help-block">Клик по картинке добавит ее к заданию. <span class="text-danger">Не забудьте СОХРАНИТЬ!</span></div>
<div id="founded-images">
{% for (var i=0; i<o.images.length; i++) { %}
	{% include('tmpl-search-image', o.images[i]); %}
{% } %}
</div>