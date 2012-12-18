<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
	<head>
		<title><?php echo $page['title']; ?></title>
		<meta http-equiv="content-Language" content="ru" />
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<meta name="description" content="<?php echo $page['description']; ?>" />
		<meta name="keywords" content="<?php echo $page['keywords']; ?>" />

		<?php foreach ($scripts as $script): ?>
			<script src="<?php echo $script; ?>" type="text/javascript"></script>
		<?php endforeach; ?>

		<?php foreach ($styles as $style): ?>
			<link href="<?php echo $style; ?>" rel="stylesheet" type="text/css" />
		<?php endforeach; ?>

		<link href='http://fonts.googleapis.com/css?family=PT+Serif:400,400italic,700|Philosopher:400,400italic,700&amp;subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    </head>

	<body>
		<div class="gl-center">
			<h1><?php echo $msg; ?></h1>
		</div>
		<span id="file_path" style="display: none;"><?php echo FILE_PATH_URL; ?></span>
    </body>

</html>
