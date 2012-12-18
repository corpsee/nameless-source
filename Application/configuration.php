<?php

return array
(
	'debug'               => FALSE,
	'timezone'            => 'Asia/Novosibirsk',
	'doctype'             => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
	'charset'             => 'UTF-8',
	'locale'              => 'ru',
	'http_port'           => 80,
	'https_port'          => 443,
	'templates_path'      => ROOT_PATH . 'Application' . DS . 'Templates' . DS,
	'templates_extension' => '.tpl',
	'database_settings'   => 'sqlite:' . ROOT_PATH . 'Application' . DS . 'corpsee.sqlite',
	'services'            => array
	(
		'auto'   => 'Framework\\ServiceProvider\\AutoProvider',
		'mailer' => 'Framework\\ServiceProvider\\SwiftmailerProvider',
	),
	'mailer_settings'     => array(),
);