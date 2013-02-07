<?php

return array
(
	'debug'               => TRUE,
	'timezone'            => 'Asia/Novosibirsk',
	'doctype'             => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
	'charset'             => 'UTF-8',
	'locale'              => 'ru',
	'http_port'           => 80,
	'https_port'          => 443,
	'templates_path'      => TEMPLATE_PATH,
	'templates_extension' => '.tpl',
	'database_settings'   => 'sqlite:' . ROOT_PATH . 'Application' . DS . 'corpsee.sqlite',
	'services'            => array
	(
		'auto'   => 'Framework\\ServiceProvider\\AutoProvider',
		'mailer' => 'Framework\\ServiceProvider\\SwiftmailerProvider',
		'logger' => 'Framework\\ServiceProvider\\MonologProvider',
	),
	'log_path'            => ROOT_PATH . DS . 'Logs' . DS,
);