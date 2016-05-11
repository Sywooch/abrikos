<div class="col-md-6 image-cell status-{%=o.status%}" id="image-choosen-wrap-{%=o.id%}">
	<div class="image-choosen-wrap">
		<a href="javascript:void(0)" onclick="showMediaDialog(this, '{%=o.id%}')" class="thumbnail" id="image-choosen-anchor-{%=o.id%}">
			<img src="{%=o.src%}?{%=Math.round(new Date().getTime() + (Math.random() * 100))%}"  id="image-choosen-{%=o.id%}" data-id="{%=o.id%}" class="image-choosen"/>
		</a>
		<div class="image-choosen-tools">
			{% if(o.order>=0) { %}
			<button class="glyphicon glyphicon-picture" onclick="questMainPicture('{%=o.order%}')" title="Сделать основной"></button>
			{% }%}
		</div>
	</div>
</div>
