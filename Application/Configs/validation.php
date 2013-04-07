<?php

return array
(
	'GalleryForm' => array
	(
		'title'       => array('noempty'),
		'description' => array('noempty'),
		'tags'        => array('noempty'),
		'create_date' => array('noempty'),
	),
	'TagForm' => array
	(
		'tag' => array('noempty'),
	),
	'UserForm' => array
	(
		'login'    => array('noempty'),
		'email'    => array('noempty', 'email'),
		'password' => array('noempty', array('min_length', 6)),
	)
);