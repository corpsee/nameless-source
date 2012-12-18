<h1>Добавить метку</h1>
<form name="form-crop" id="form-crop" action="" method="post" enctype="multipart/form-data">
		<div class="control-group">
			<label for="tag" class="left">Метка</label>
			<div class="control">
				<input class="text validation" type="text" name="tag" id="tag" value="" />
				<div class="msg"></div>
			</div>
		</div>

		<div class="control-group">
			<label for="pictures" class="left">Изображения</label>
			<div class="control">
				<select name="pictures[]" id="pictures" multiple="multiple" class="chosen">
					<?php foreach ($pictures as $picture):?>
						<option><?php echo $picture['title']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>

	<div class="action">
		<input class="submit" type="submit" name="submit" value="Добавить" />
	</div>
</form>