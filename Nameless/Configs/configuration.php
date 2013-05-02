<?php

return array
(
	// production, test, debug
	'environment'         => 'debug',
	'timezone'            => 'UTC',
	'locale'              => 'en',
	'http_port'           => 80,
	'https_port'          => 443,
	'templates_extension' => 'tpl',
	'modules'            => array
	(
		//'Database','Validation','Auto','Mailer','Logger','Assets',
	),
	'cache_path'          => APPLICATION_PATH . 'Cache' . DS,
);