<?php

return array
(
	// ErrorController
	/*'server_error' => array
	(
		'pattern'      => '/error/{code}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controllers\\ErrorController::errorServer',
			'code'       => 500,
		),
	),
	'server_error_slash' => array
	(
		'pattern'      => '/error/{code}/',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controllers\\ErrorController::errorServer',
			'code'       => 500,
		),
	),*/

	// IndexController
	'index' => array
	(
		'pattern'      => '/',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controllers\\IndexController::index',
		),
	),
);