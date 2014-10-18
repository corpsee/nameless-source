Nameless Framework
==================
Yet another framework based on Symfony Components.
Examples
--------
Install dependencies (vendor libs):
```bash
cd /var/www/nameless.local
php composer.phar install
```
Debug version of *index.php*:
```php
error_reporting(-1);
ini_set('display_errors', 1);

define('DS',               DIRECTORY_SEPARATOR);
define('START_TIME',       microtime(TRUE));
define('START_MEMORY',     memory_get_usage());
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
```
Production version of *index.php*:
```php
define('DS',               DIRECTORY_SEPARATOR);
define('START_TIME',       microtime(TRUE));
define('START_MEMORY',     memory_get_usage());
define('ROOT_PATH',        dirname(__DIR__) . DS);
define('NAMELESS_PATH',    ROOT_PATH . 'Nameless' . DS);
define('APPLICATION_PATH', ROOT_PATH . 'Application' . DS);
define('CONFIG_PATH',      APPLICATION_PATH . 'configs' . DS);
define('PUBLIC_PATH',      ROOT_PATH . 'www' . DS);
define('FILE_PATH',        PUBLIC_PATH . 'files' . DS);
define('FILE_PATH_URL',    '/files/');

require_once ROOT_PATH . 'vendor' . DS . 'autoload.php';

// production mode with cache
use Nameless\Core\HttpCache;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Nameless\Core\Kernel;

$options = array
(
	'debug'                  => FALSE,
	'default_ttl'            => 0,
	'private_headers'        => ['Authorization', 'Cookie'],
	'allow_reload'           => TRUE,
	'allow_revalidate'       => TRUE,
	'stale_while_revalidate' => 2,
	'stale_if_error'         => 60,
);

$framework = new Kernel();
$framework = new HttpCache(new Kernel(), new Store(APPLICATION_PATH . 'cache'), NULL, $options);
```
For more examples please see the Application directory.
Tests
-----
Run tests:
```bash
cd /var/www/nameless.local/Bin
phpunit -c ../Nameless/phpunit.xml
```
License
-------
For the full copyright and license information, please see the LICENSE.