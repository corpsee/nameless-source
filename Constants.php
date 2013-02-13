<?php

define('DS', DIRECTORY_SEPARATOR);

define('APPLICATION_PATH', ROOT_PATH . 'Application' . DS);
define('TEMPLATE_PATH',    APPLICATION_PATH . 'Templates' . DS);
define('CONFIG_PATH',      APPLICATION_PATH . 'Configs' . DS);

define('PUBLIC_PATH', ROOT_PATH . 'Public' . DS);
define('FILE_PATH',   PUBLIC_PATH . 'files' . DS);

define('FILE_PATH_URL', '/files/');
define('ICON_PATH_URL', FILE_PATH_URL . 'icons/');
define('STYLE_PATH_URL', FILE_PATH_URL . 'styles/');
define('SCRIPT_PATH_URL', FILE_PATH_URL . 'scripts/');