<?php

// for debug only
error_reporting(-1);
ini_set('display_errors', 1);

// constants
define('DS', DIRECTORY_SEPARATOR);

define('START_TIME',   microtime(TRUE));
define('START_MEMORY', memory_get_usage());

define('ROOT_PATH',        dirname(__DIR__) . DS);
define('NAMELESS_PATH',    ROOT_PATH . 'Nameless' . DS);
define('APPLICATION_PATH', ROOT_PATH . 'Application' . DS);
define('CONFIG_PATH',      APPLICATION_PATH . 'configs' . DS);
define('PUBLIC_PATH',      ROOT_PATH . 'www' . DS);
define('FILE_PATH',        PUBLIC_PATH . 'files' . DS);
define('FILE_PATH_URL',    '/files/');

require_once ROOT_PATH . 'vendor' . DS . 'autoload.php';

use Nameless\Core\Kernel;

$framework = new Kernel();
$framework->run();