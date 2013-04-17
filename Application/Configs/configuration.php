<?php

return array
(
	// production, test, debug
	'environment'         => 'debug',
	'timezone'            => 'Asia/Novosibirsk',
	'charset'             => 'UTF-8',
	'locale'              => 'ru',
	'http_port'           => 80,
	'https_port'          => 443,
	'templates_path'      => TEMPLATE_PATH,
	'templates_extension' => '.tpl',
	'database_settings'   => '',
	'services'            => array
	(
		//'Database',
		//'Validation',
		//'Auto',
		//'Mailer',
		//'Logger',
	),
	'log_path'            => APPLICATION_PATH . 'Logs' . DS,
	'cache_path'          => APPLICATION_PATH . 'Cache' . DS,
	'yuicompressor_path'  => ROOT_PATH . 'yuicompressor-2.4.7.jar',
	'java_path'           => '',
);