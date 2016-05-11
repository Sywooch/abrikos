<div id="tour-view-{%=o.tour.id%}" class="tour-view list-group-item"  data-id="{%=o.tour.id%}">
	{%=o.tour.sort%}
	<div class="tour-view-image-container"><img src="{%=o.images[0].src%}" alt="" /></div>
	<button onclick="tourSelect({%=o.tour.id%})" class="btn btn-info">{%=o.tour.answer%}</button>
	{% if(o.tour.enabled==0) { %}
	<span class="badge">Недоступно</span>
	{% }%}

</div>