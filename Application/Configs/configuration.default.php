<?php

return array
(
	'debug'               => TRUE,
	'timezone'            => 'UTC',
	'charset'             => 'UTF-8',
	'locale'              => 'en',
	'http_port'           => 80,
	'https_port'          => 443,
	'templates_path'      => TEMPLATE_PATH,
	'templates_extension' => '.tpl',
	'database_settings'   => 'sqlite:' . ROOT_PATH . 'Application' . DS . 'corpsee.sqlite',
	'services'            => array(),
	'log_path'            => ROOT_PATH . DS . 'Logs' . DS,
	'cache_path'          => ROOT_PATH . DS . 'Cache' . DS,
	'yuicompressor_path'  => ROOT_PATH . 'yuicompressor-2.4.7.jar',
	'java_path'           => 'C:\Program files\Java\jre6\bin\java.exe',
);