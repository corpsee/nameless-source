<h1>Изменить метку</h1>
<form name="form-crop" id="form-crop" action="" method="post" enctype="multipart/form-data">
	<div class="control-group">
		<label for="tag" class="left">Метка</label>
		<div class="control">
			<input class="text validation" type="text" name="tag" id="tag" value="<?php echo $values['tag']; ?>" />
			<div class="msg"></div>
		</div>
	</div>

	<div class="control-group">
		<label for="pictures" class="left">Изображения</label>
		<div class="control">
			<select name="pictures[]" id="pictures" multiple="multiple" class="chosen">
				<?php foreach ($pictures as $picture):?>
					<option
					<?php foreach ($values['pictures'] as $p): ?>
					<?php if ($p['id'] == $picture['id']): ?>
						selected="selected"
					<?php break; endif;?>
					<?php endforeach; ?>>
					<?php echo $picture['title']; ?>
					</option>
				<?php endforeach; ?>
			</select>
			<div class="msg"></div>
		</div>
	</div>

	<div class="action">
		<input class="submit" type="submit" name="submit" value="Изменить" />
	</div>
</form>