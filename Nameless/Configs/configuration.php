<?php

return array
(
	// production, test, debug
	'environment'         => 'debug',
	'timezone'            => 'UTC',
	'locale'              => 'en',
	'http_port'           => 80,
	'https_port'          => 443,
	'templates_path'      => TEMPLATE_PATH,
	'templates_extension' => 'tpl',
	'modules'            => array
	(
		//'Database','Validation','Auto','Mailer','Logger',
	),
	'cache_path'          => APPLICATION_PATH . 'Cache' . DS,
);