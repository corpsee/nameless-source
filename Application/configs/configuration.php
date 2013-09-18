<?php

return array
(
	// production, test, debug
	'environment' => 'debug',
	'timezone'    => 'Asia/Novosibirsk',
	'locale'      => 'ru',
	'language'    => 'ru',

	'routes'      => array
	(
		'index' => array
		(
			'pattern'      => '/',
			'defaults'     => array
			(
				'_controller' => 'Application\\Controllers\\IndexController::index',
			),
		),
	),

	'modules'     => array
	(
		'Logger', 'Assets', 'Imager', //'Database','Validation','Auto','Mailer',
	),

	'assets' => array
	(
		'java_path' => 'C:\\Program files\\Java\\jre6\\bin\\java.exe',
	),
);