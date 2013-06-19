<?php

return array
(
	// production, test, debug
	'environment' => 'production',
	'timezone'    => 'Asia/Novosibirsk',
	'locale'      => 'ru',
	'language'    => 'ru',
	'modules'     => array
	(
		'Logger', 'Assets', //'Database','Validation','Auto','Mailer',
	),
	'templates_error_path' => APPLICATION_PATH . 'Templates' . DS,
);