<?php

/**
 * Default Nameless configuration
 */
return array
(
	'environment'                  => 'production',
	'timezone'                     => 'UTC',
	'locale'                       => 'en',
	'language'                     => 'en',
	'http_port'                    => 80,
	'https_port'                   => 443,
	'templates_extension'          => 'tpl',
	'cache_path'                   => ROOT_PATH . 'Cache' . DS,
	'templates_path'               => APPLICATION_PATH . 'Templates' . DS,
	'templates_error_path'         => APPLICATION_PATH . 'Templates' . DS,
	'default_templates_error_path' => ROOT_PATH . 'Nameless' . DS . 'Core' . DS . 'Templates' . DS,
);