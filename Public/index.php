<?php

// только для отладки
error_reporting(-1);
ini_set('display_errors', 1);
$start_point = microtime(TRUE);

// константы
define('DS',        DIRECTORY_SEPARATOR);
define('ROOT_PATH', dirname(__DIR__) . DS);

define('APPLICATION_PATH', ROOT_PATH . 'Application' . DS);
define('TEMPLATE_PATH',    APPLICATION_PATH . 'Templates' . DS);
define('CONFIG_PATH',      APPLICATION_PATH . 'Configs' . DS);

define('PUBLIC_PATH', ROOT_PATH . 'Public' . DS);
define('FILE_PATH',   PUBLIC_PATH . 'files' . DS);

define('FILE_PATH_URL',   '/files/');

require_once(ROOT_PATH . 'Vendors' . DS . 'autoload.php');

use Nameless\Core\Kernel;

$framework = new Kernel($start_point);
$framework->run();