<?php

define('DS', DIRECTORY_SEPARATOR);

define('START_TIME', microtime(true));
define('START_MEMORY', memory_get_usage());

define('ROOT_PATH', dirname(__DIR__) . '/');
define('APPLICATION_PATH', ROOT_PATH . 'Application' . DS);
define('CONFIG_PATH', APPLICATION_PATH . 'configs' . DS);
define('PUBLIC_PATH', ROOT_PATH . 'Public' . DS);
define('FILE_PATH', PUBLIC_PATH . 'files' . DS);
define('FILE_PATH_URL', '/files/');

require_once ROOT_PATH . 'vendor' . DS . 'autoload.php';