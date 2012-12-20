<?php

error_reporting(-1);
ini_set('display_errors', 1);

// загружать ресурсы с субдоменов или нет
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', dirname(__DIR__) . DS);

require_once ROOT_PATH .'Vendors/autoload.php';

use Assetic\Asset\AssetCollection;
use Assetic\Filter\Yui\CssCompressorFilter;
use Assetic\Filter\Yui\JsCompressorFilter;
use Assetic\Filter\GoogleClosure\CompilerApiFilter;
use Assetic\Asset\FileAsset;

$css = new AssetCollection
(
	array
	(
		new FileAsset(ROOT_PATH . 'Public/Files/s/main.css'),
		new FileAsset(ROOT_PATH . 'Public/Files/s/normalize-2.0.1.css'),
	),
	array(new CssCompressorFilter(ROOT_PATH . 'yuicompressor-2.4.jar', 'C:\Program Files\Java\jre6\bin\java.exe'))
);

$js = new AssetCollection
(
	array
	(
		new FileAsset(ROOT_PATH . 'Public/Files/j/gallery.js'),
		new FileAsset(ROOT_PATH . 'Public/Files/j/jcrop.js'),
	),
	//array(new CompilerApiFilter())
	array(new JsCompressorFilter(ROOT_PATH . 'yuicompressor-2.4.jar', 'C:\Program Files\Java\jre6\bin\java.exe'))
);

echo '<pre>'; print_r($css->dump());
//echo '<pre>'; print_r($js->dump());