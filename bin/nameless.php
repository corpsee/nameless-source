<?php

error_reporting(-1);
ini_set('display_errors', 1);

define('ROOT_PATH',        dirname(__DIR__) . '/');
define('APPLICATION_PATH', ROOT_PATH . 'Application/');
define('CONFIG_PATH',      APPLICATION_PATH . 'configs/');

require_once ROOT_PATH . 'vendor/autoload.php';

use Nameless\Core\Application;
use Nameless\Core\Console;

$console = new Console(new Application(), 'nameless', '0.4.0');
$console->run();