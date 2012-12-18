<h1>Войти</h1>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
	<div class="control-group">
		<label for="login" class="left">Логин</label>
		<div class="control">
			<input class="text" type="text" name="login" id="login" value="" />
			<p class="help">Введите ваш логин</p>
		</div>
	</div>
	<div class="control-group">
		<label for="password" class="left">Пароль</label>
		<div class="control">
			<input class="text" type="password" name="password" id="password" value="" />
			<p class="help">Введите ваш пароль</p>
		</div>
	</div>
	<div class="action">
		<input class="submit" type="submit" name="submit" value="Войти" />
	</div>

</form>
