<div id="add-media-dialogs-wrapper">
	<div class="modal" id="add-media-dialog" tabindex="-2" role="dialog" aria-labelledby="mediaModalLabel" data-backdrop="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="mediaModalLabel">Добавить фото</h4>
				</div>
				<div class="modal-body">
					<input style="display: none"  id="input-file" type="file" onchange="doUpload(this, 'disk', '{%=o.id%}', '{%=o.index%}')">
					<div class="row upload-buttons-wrapper">
						<div class="col-md-6">
							<a href="javascript:void(0)"  class="btn btn-success" onclick="javascript:$('#input-file').click()">Загрузить с диска</a>
							<small>Если у Вас не загружаются файлы, то возможно не хватает памяти на Вашем устройстве</small>
						</div>
						<form class="col-md-6" onsubmit="return doUpload($('#image-link').val(), 'remote', '{%=o.id%}', '{%=o.index%}')">
							Вставьте ссылку на изображение.
							<input id="image-link" value="" name="imageLink" /><button type="submit">Загрузить</button>
							<br/><kbd>Enter</kbd> - для начала загрузки
						</form>
						<!--div class="col-md-4">
							<a  class="glyphicon glyphicon-sd-video" data-toggle="modal" data-target="#youtubeModal" href="#"></a>
						</div-->
					</div>
					<div id="progress-wrap"></div>
					Картинки, чьи размеры превышают <?=$this->context->module->maxWidth?>х<?=$this->context->module->maxHeight?> px будут автоматически уменьшены
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id="btn-media-dialog-close">Закрыть</button>
				</div>
			</div>
		</div>
	</div>

