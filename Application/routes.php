<?php

return array
(
	// ErrorController
	'server_error' => array
	(
		'pattern'      => '/error/{code}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\ErrorController::errorServer',
			'code'       => 500,
		),
	),
	'server_error_slash' => array
	(
		'pattern'      => '/error/{code}/',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\ErrorController::errorServer',
			'code'       => 500,
		),
	),
	'admin_error' => array
	(
		'pattern'      => '/admin/error/{code}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\ErrorController::errorAdmin',
			'code'         => NULL,
		),
	),
	'admin_error_slash' => array
	(
		'pattern'      => '/admin/error/{code}/',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\ErrorController::errorAdmin',
			'code'         => NULL,
		),
	),

	// IndexController
	'index' => array
	(
		'pattern'      => '/',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\IndexController::index',
		),
	),
	'bytag' => array
	(
		'pattern'      => '/bytag{slash}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\IndexController::byTag',
			'slash'       => '',
		),
		'requirements' => array ('slash'       => '/?'),
	),
	'onetag' => array
	(
		'pattern'      => '/onetag/{tag}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\IndexController::oneTag',
			'tag'         => NULL,
		),
	),
	'onetag_slash' => array
	(
		'pattern'      => '/onetag/{tag}/',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\IndexController::oneTag',
			'tag'         => NULL,
		),
	),
	'css' => array
	(
		'pattern'      => '/css{slash}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\IndexController::css',
			'slash'       => '',
		),
		'requirements' => array ('slash'       => '/?'),
	),

	// AdminController
	'admin' => array
	(
		'pattern'      => '/admin{slash}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\AdminController::login',
			'slash'         => '',
		),
		'requirements' => array('slash'       => '/?(index|login)?/?'),
	),
	'logout' => array
	(
		'pattern'      => '/admin/logout{slash}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\AdminController::logout',
			'slash'         => '',
		),
		'requirements' => array('slash'       => '/?'),
	),

	// GalleryController
	'gallery' => array
	(
		'pattern'      => '/admin/gallery{slash}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\GalleryController::index',
			'slash'         => '',
		),
		'requirements' => array('slash'       => '/?(index)?/?'),
	),
	'gallery_add' => array
	(
		'pattern'      => '/admin/gallery/add',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\GalleryController::add',
		),
	),
	'gallery_crop' => array
	(
		'pattern'      => '/admin/gallery/crop/{image}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\GalleryController::crop',
			'image'       => NULL,
		),
	),
	'gallery_result' => array
	(
		'pattern'      => '/admin/gallery/result/{image}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\GalleryController::result',
			'image'       => NULL,
		),
	),
	'gallery_edit' => array
	(
		'pattern'      => '/admin/gallery/edit/{id}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\GalleryController::edit',
			'id'       => NULL,
		),
	),
	'gallery_editimage' => array
	(
		'pattern'      => '/admin/gallery/editimage/{id}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\GalleryController::editimage',
			'id'       => NULL,
		),
	),
	'gallery_delete' => array
	(
		'pattern'      => '/admin/gallery/delete/{id}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\GalleryController::delete',
			'id'       => NULL,
		),
	),

	// TagController
	'tag' => array
	(
		'pattern'      => '/admin/tag{slash}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\TagController::index',
			'slash'         => '',
		),
		'requirements' => array('slash'       => '/?(index)?/?'),
	),
	'tag_add' => array
	(
		'pattern'      => '/admin/tag/add',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\TagController::add',
		),
	),
	'tag_edit' => array
	(
		'pattern'      => '/admin/tag/edit/{id}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\TagController::edit',
			'id'         => NULL,
		),
	),
	'tag_delete' => array
	(
		'pattern'      => '/admin/tag/delete/{id}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\TagController::delete',
			'id'         => NULL,
		),
	),
);