<div id="add-media-dialogs-wrapper">
	<div class="modal" id="add-media-dialog" tabindex="-2" role="dialog" aria-labelledby="mediaModalLabel" data-backdrop="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="mediaModalLabel">Добавить фото</h4>
				</div>
				<div class="modal-body">
					<input style="display: none"  id="input-file" type="file" onchange="doUpload(this, 'disk', {%=o.id%}, '{%=o.object%}')">
					<div class="row upload-buttons-wrapper">
						<div class="col-md-6">
							<a href="javascript:void(0)"  class="btn btn-success" onclick="javascript:$('#input-file').click()">Загрузить с диска</a>
						</div>
						<form class="col-md-6" onsubmit="return doUpload($('#image-link').val(), 'remote', {%=o.id%}, '{%=o.object%}')">
							Вставьте ссылку на изображение.
							<input id="image-link" value="" name="imageLink" onchange="$(this.form).submit()"/>
							<br/><kbd>Enter</kbd> - для начала загрузки
						</form>
						<!--div class="col-md-4">
							<a  class="glyphicon glyphicon-sd-video" data-toggle="modal" data-target="#youtubeModal" href="#"></a>
						</div-->
					</div>
					Изображения масштабируются если превышают ширину:
					<ul>
						<li>600px для вопроса</li>
						<li>200px для ответа</li>
						<li>400px для обложки викторины</li>
					</ul>
					Иначе копируются оригиналы, можно анимированные
					<div id="progress-wrap"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id="btn-media-dialog-close">Закрыть</button>
				</div>
			</div>
		</div>
	</div>

