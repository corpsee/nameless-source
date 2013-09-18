<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?= $title; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?= $description; ?>">
	<meta name="keywords" content="<?= $keywords; ?>">
	<link rel="shortcut icon" href="/files/bootstrap/img/favicon.png">

	<?= $styles; ?>
</head>

<body>

<?= $this->subTemplate($subtemplate); ?>
<?= $scripts; ?>

</body>
</html>

