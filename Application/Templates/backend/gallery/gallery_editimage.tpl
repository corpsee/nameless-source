<h1>Изменить картинку</h1>
<form name="form-crop" id="form-crop" action="" method="post" enctype="multipart/form-data">
	<div class="control-group">
		<label for="file" class="left">Изображение</label>
		<div class="control">
			<input type="hidden" name="MAX_FILE_SIZE" value="50000000" />
			<input class="file validation" type="file" name="file" id="file" value="" />
			<div class="msg"></div>
		</div>
	</div>
	<div class="action">
		<input class="submit" type="submit" name="submit" value="Загрузить" />
	</div>
</form>