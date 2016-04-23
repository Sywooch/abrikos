<div id="add-media-dialogs-wrapper">
	<div class="modal" id="add-media-dialog" tabindex="-2" role="dialog" aria-labelledby="mediaModalLabel" data-backdrop="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="mediaModalLabel">Добавить фото/видео</h4>
				</div>
				<div class="modal-body">
					<input style="display: none"  id="input-file" type="file" onchange="doUpload(this, 'disk', {%=o.id%}, '{%=o.type%}')">
					<div class="row upload-buttons-wrapper">
						<div class="col-md-6">
							<span  class="glyphicon glyphicon-upload" onclick="javascript:$('#input-file').click()"></span><br/>
							Загрузить с диска
						</div>
						<div class="col-md-6" >
							<span  class="glyphicon glyphicon-link" data-toggle="modal" data-target="#urlModal"></span><br/>
							Загрузить с другого сайта
						</div>
						<!--div class="col-md-4">
							<a  class="glyphicon glyphicon-sd-video" data-toggle="modal" data-target="#youtubeModal" href="#"></a>
						</div-->
					</div>
					<progress class="collapse"></progress>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id="btn-media-dialog-close">Закрыть</button>
				</div>
			</div>
		</div>
	</div>


	<div class="modal" id="urlModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"  data-backdrop="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Загрузка по ссылке</h4>
				</div>
				<div class="modal-body">
					Вставьте ссылку на изображение<input id="image-link">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" onclick="doUpload($('#image-link').val(), 'remote', {%=o.id%}, '{%=o.type%}')" data-dismiss="modal">Save changes</button>
				</div>
			</div>
		</div>
	</div>


	<div class="modal fade" id="youtubeModal" tabindex="-1" role="dialog" aria-labelledby="youtubeModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="youtubeModalLabel">Вставить ролик Youtube</h4>
				</div>
				<div class="modal-body">
					Вставьте ссылку на ролик<input id="youtube-link">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" onclick="doYoutube($('#yotube-link').val())">Готово</button>
				</div>
			</div>
		</div>
	</div>
</div>