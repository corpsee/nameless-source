<?php

define('DS', DIRECTORY_SEPARATOR);

define('START_TIME',   microtime(TRUE));
define('START_MEMORY', memory_get_usage());

define('ROOT_PATH',        dirname(dirname(__DIR__)) . DS);
define('NAMELESS_PATH',    ROOT_PATH . 'Nameless' . DS);
define('APPLICATION_PATH', ROOT_PATH . 'Application' . DS);
define('CONFIG_PATH',      APPLICATION_PATH . 'configs' . DS);
define('PUBLIC_PATH',      ROOT_PATH . 'Public' . DS);
define('FILE_PATH',        PUBLIC_PATH . 'files' . DS);
define('FILE_PATH_URL',    '/files/');

require_once ROOT_PATH . 'Vendors' . DS . 'autoload.php';