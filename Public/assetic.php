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
define('TEMPLATE_PATH',    APPLICATION_PATH . 'Templates' . DS);
define('CONFIG_PATH',      APPLICATION_PATH . 'Configs' . DS);
define('PUBLIC_PATH',      ROOT_PATH . 'Public' . DS);
define('FILE_PATH',        PUBLIC_PATH . 'files' . DS);

define('FILE_PATH_URL',   '/files/');

require_once(ROOT_PATH . 'Vendors' . DS . 'autoload.php');

use Assetic\Factory\AssetFactory;
use Assetic\Factory\LazyAssetManager;
use Assetic\Factory\Worker\CacheBustingWorker;
use Assetic\Asset\FileAsset;
use Assetic\Asset\AssetCollection;
use Assetic\AssetManager;
use Assetic\AssetWriter;
use Assetic\FilterManager;
use Assetic\Filter\Yui\CssCompressorFilter;
use Assetic\Filter\Yui\JsCompressorFilter;

$root_path = dirname(__DIR__) . DS  . 'Public' . DS . 'files' . DS;

$ac_js = new AssetCollection
(
	array
	(
	    new FileAsset($root_path . 'scripts/jquery-1.10.1.js'),
		new FileAsset($root_path . 'bootstrap/js/bootstrap.js'),
	)/*,
	array
	(
		new JsCompressorFilter(ROOT_PATH . 'Nameless' . DS . 'Modules' . DS . 'Assets' . DS . 'yuicompressor-2.4.7.jar', 'C:\\Program files\\Java\\jre6\\bin\\java.exe'),
	)*/
);

$ac_css = new AssetCollection(
	array
	(
	    new FileAsset($root_path . 'bootstrap/css/bootstrap.css'),
		new FileAsset($root_path . 'bootstrap/css/bootstrap-responsive.css'),
	)/*,
	array
	(
		new CssCompressorFilter(ROOT_PATH . 'Nameless' . DS . 'Modules' . DS . 'Assets' . DS . 'yuicompressor-2.4.7.jar', 'C:\\Program files\\Java\\jre6\\bin\\java.exe'),
	)*/
);
$ac_css->setValues(array('Y' => '2013'));

echo '<pre>';

$am = new AssetManager();
$am->set('frontend_js', $ac_js);
$am->set('frontend_css', $ac_css);

$fm = new FilterManager();
$fm->set('css', new CssCompressorFilter(ROOT_PATH . 'Nameless' . DS . 'Modules' . DS . 'Assets' . DS . 'yuicompressor-2.4.7.jar', 'C:\\Program files\\Java\\jre6\\bin\\java.exe'));
$fm->set('js', new JsCompressorFilter(ROOT_PATH . 'Nameless' . DS . 'Modules' . DS . 'Assets' . DS . 'yuicompressor-2.4.7.jar', 'C:\\Program files\\Java\\jre6\\bin\\java.exe'));

$factory = new AssetFactory($root_path, TRUE);
$factory->setAssetManager($am);
$factory->setFilterManager($fm);
$factory->setDefaultOutput('compiled/*.css');
$factory->addWorker(new CacheBustingWorker(new LazyAssetManager($factory)));

$css = $factory->createAsset(array('@frontend_css'), array('css'), array('name' => 'frontend.min', 'vars' => array('Y')));
//$js  = $factory->createAsset(array('@frontend_js'), array('js'));
echo $css->getTargetPath();

$writer = new AssetWriter(dirname(__DIR__) . DS  . 'Public' . DS . 'files');
$writer->writeAsset()
//$writer->writeAsset($css);
//$writer->writeAsset($js);
