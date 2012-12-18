		<img class="crop" id="cropbox" src="<?php echo FILE_PATH_URL; ?>pictures/x/<?php echo $image['image']; ?>.jpg" width="<?php echo $image['width']; ?>" height="<?php echo $image['height']; ?>" />

		<form action="" method="post" name="cropform" id="cropform">
			<input type="hidden" id="x" name="x" />
			<input type="hidden" id="y" name="y" />
			<input type="hidden" id="w" name="w" />
			<input type="hidden" id="h" name="h" />
			<input class="submit" type="submit" name="submit" value="Обрезать" />
		</form>