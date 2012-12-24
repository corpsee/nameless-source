<?php

return array
(
	'Application\\Controller\\GalleryController' => array
	(
		'index'     => array('ROLE_REGISTERED'),
		'add'       => array('ROLE_ADMIN'),
		'crop'      => array('ROLE_ADMIN'),
		'result'    => array('ROLE_ADMIN'),
		'edit'      => array('ROLE_ADMIN'),
		'editimage' => array('ROLE_ADMIN'),
		'delete'    => array('ROLE_ADMIN'),
	),
	'Application\\Controller\\TagController' => array
	(
		'index'  => array('ROLE_REGISTERED'),
		'add'    => array('ROLE_ADMIN'),
		'edit'   => array('ROLE_ADMIN'),
		'delete' => array('ROLE_ADMIN'),
	),
	'Application\\Controller\\ErrorController' => array
	(
		'errorAdmin'  => array('ROLE_REGISTERED'),
	)
);