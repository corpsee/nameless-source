<!DOCTYPE html>
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
</head>
<body>

</body>
</html>