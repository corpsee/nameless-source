<?php

error_reporting(-1);
ini_set('display_errors', 1);

// загружать ресурсы с субдоменов или нет
define('DS', DIRECTORY_SEPARATOR);
define('SUBDOMENS', FALSE);

define('ROOT_PATH', dirname(__DIR__) . DS);

define('APPLICATION_PATH', ROOT_PATH . 'Application' . DS);
define('TEMPLATE_PATH', APPLICATION_PATH . 'Templates' . DS);
define('CONFIG_PATH', APPLICATION_PATH . 'Configs' . DS);

define('FILE_PATH', ROOT_PATH . 'Public' . DS . 'Files' . DS);
define('FILE_PATH_URL', '/Files/');

function pathToURL ($path)
{
	//echo $path . '<br />';
	$path = str_replace('/', DS, $path);
	$path = str_replace(FILE_PATH, FILE_PATH_URL, $path);
	$path = str_replace(DS, '/', $path);
	//echo $path . '<br /><br />';
	return $path;
}

function URLToPath ($path)
{
	//echo $path . '<br />';
	$path = str_replace(FILE_PATH_URL, FILE_PATH, $path);
	$path = str_replace('/', DS, $path);
	//echo $path . '<br /><br />';
	return $path;
}

if (SUBDOMENS)
{
	$host = $_SERVER['HTTP_HOST'];
	if ($position = stripos($_SERVER['HTTP_HOST'], 'www.') !== FALSE)
	{
		$host = substr($_SERVER['HTTP_HOST'], 4);
	}

	define('I_FILE_PATH', '//' . 'i.' . $host . '/');
	define('S_FILE_PATH', '//' . 's.' . $host . '/');
	define('J_FILE_PATH', '//' . 'j.' . $host . '/');
}
else
{
	define('I_FILE_PATH', FILE_PATH_URL . 'i/');
	define('S_FILE_PATH', FILE_PATH_URL . 's/');
	define('J_FILE_PATH', FILE_PATH_URL . 'j/');
}

require_once ROOT_PATH .'Vendors/autoload.php';
require_once ROOT_PATH .'Functions.php';

use Framework\HttpCache;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Framework\Kernel;

$options = array
(
	'debug'                  => FALSE,
	'default_ttl'            => 0,
	'private_headers'        => array('Authorization', 'Cookie'),
	'allow_reload'           => TRUE,
	'allow_revalidate'       => TRUE,
	'stale_while_revalidate' => 2,
	'stale_if_error'         => 60,
);

$framework = new Kernel();
$framework = new HttpCache(new Kernel(), new Store(ROOT_PATH . 'Cache'), NULL, $options);
$framework->run();

//echo '<pre>';
//var_dump(preg_match('![\w\p{L}\p{Nd}]+!i', 'привет123'));
//var_dump(preg_match('![\p{L}\p{Nd}]+!i', 'привет123_@@'));

//echo hash_hmac('sha1', '201986', 'necrosis') . '<br />';
//echo hash_hmac('sha1', 'registered', 'registered') . '<br />';

/*ob_start();
include 'http://online.corpsee.com/index.php';
$array = ob_get_clean();
print_r(unserialize($array));*/

//mail('poisoncorpsee@gmail.com', 'mail', 'mail');

/*$a = NULL;
if (!isset($a)) { echo 'true' . '<br />'; }
if (empty($a)) { echo 'true' . '<br />'; }
if (is_null($a)) { echo 'true' . '<br />'; }
if (!$a) { echo 'true' . '<br />'; }*/

//echo '<pre>';
//print_r($_SERVER);
//print_r($_REQUEST);

/*$emails = array
(
	'poisoncorpsee@gmail.com',
	'poison.corpsee@gmail.com',
	'poisoncorpsee@gmail.google.com',
	'poisoncorpsee@gmail.google.at.com',
	'poisoncorpsee@g.com',
	'poisoncorpsee@g.m.com',
	'poison..corpsee@gmail.com',
	'poison...corpsee@gmail.com',
	'poisoncorpsee@gmail..com',
	'poisoncorpsee@гмаил.com',
	'poisoncorpsee@гмаил.ком',
	'пойзонкорпс@гмаил.ком',
	'пойзонкорпс@гмаил..пр.ком',
	'пойзон+корпс@гмаил.пр.ком',
	'poisoncorpsee@@gmail.com',
	'poison@corpsee@gmail.com',
);

foreach ($emails as $email)
{
	if (preg_match('!^[-\p{L}\p{Nd}_\.+]{1,}@([-\p{L}\p{Nd}_]{1,}\.){1,}[\p{L}\p{Nd}]{2,4}$!iu', $email))
	{
		echo $email . ' - валидный<br />';
	}
	else
	{
		echo $email . ' - невалидный<br />';
	}
}*/

/*$db = new Framework\Database('sqlite:' . ROOT_PATH . 'Application' . DIRECTORY_SEPARATOR . 'corpsee.sqlite');

$one   = $db->selectOne("SELECT * FROM `tbl_groups` WHERE `id` = 5");
$many  = $db->selectMany("SELECT * FROM `tbl_groups`");
$col   = $db->selectColumn("SELECT * FROM `tbl_groups`");
$null  = $db->selectOne("SELECT * FROM `tbl_groups` WHERE `id` = 10");

echo '<pre>'; var_dump(array($one, $many, $col, $null)); echo '</pre>';*/
//phpinfo();

/*date_default_timezone_set('Asia/Novosibirsk');

$dates = array
(
	'15.11.2012:12.00.00',

	'01.01.2007:12.00.00',
	'01.01.2008:12.00.00',
	'01.01.2008:12.00.00',
	'01.01.2008:12.00.00',
	'01.09.2009:12.00.00',
	'01.09.2009:12.00.00',
	'01.09.2009:12.00.00',
	'01.10.2009:12.00.00',
	'01.01.2010:12.00.00',
	'01.01.2010:12.00.00',
	'01.01.2010:12.00.00',
	'01.01.2010:12.00.00',
	'01.09.2010:12.00.00',
	'01.04.2010:12.00.00',
	'01.07.2010:12.00.00',
	'01.07.2010:12.00.00',
	'01.09.2010:12.00.00',
	'01.08.2010:12.00.00',
	'22.10.2010:12.00.00',
	'22.10.2010:12.00.00',
	'17.12.2010:12.00.00',
	'01.09.2010:12.00.00',
	'01.09.2010:12.00.00',
	'01.11.2010:12.00.00',
	'01.11.2010:12.00.00',
	'05.07.2011:12.00.00',
	'01.06.2011:12.00.00',
	'01.06.2011:12.00.00',
	'01.01.2011:12.00.00',
	'08.08.2011:12.00.00',
	'24.11.2011:12.00.00',
	'01.10.2011:12.00.00',
	'10.10.2011:12.00.00',
	'01.10.2011:12.00.00',
	'27.11.2011:12.00.00',
	'17.05.2012:12.00.00',
	'28.08.2012:12.00.00',

);

foreach ($dates as $date)
{
	$d = \DateTime::createFromFormat('d.m.Y:H.i.s', $date);
	echo $d->format('U') . '<br />';
}*/

/*
Проверка работы часовых поясов
date_default_timezone_set('Asia/Novosibirsk');

$dt1 = new \DateTime('now', new \DateTimeZone('Asia/Novosibirsk'));
$dt2 = new \DateTime('now', new \DateTimeZone('America/Edmonton'));
$dt3 = new \DateTime('now', new \DateTimeZone('UTC'));
$dt4 = new \DateTime('now');

echo '<pre>';
echo 'time            : ' . time() . ' // ' . date('d.m.Y:H.i.s') . '<br />';
echo 'Asia/Novosibirsk: ' . $dt1->format('U') . ' // ' . $dt1->format('d.m.Y:H.i.s') . '<br />';
echo 'America/Edmonton: ' . $dt2->format('U') . ' // ' . $dt2->format('d.m.Y:H.i.s') . '<br />';
echo 'UTC             : ' . $dt3->format('U') . ' // ' . $dt3->format('d.m.Y:H.i.s') . '<br />';
echo '---             : ' . $dt4->format('U') . ' // ' . $dt4->format('d.m.Y:H.i.s') . '<br />';

time            : 1352878777 // 14.11.2012:14.39.37
Asia/Novosibirsk: 1352878777 // 14.11.2012:14.39.37
America/Edmonton: 1352878777 // 14.11.2012:00.39.37
UTC             : 1352878777 // 14.11.2012:07.39.37
---             : 1352878777 // 14.11.2012:14.39.37

*/