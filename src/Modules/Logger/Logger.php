<?php

/**
 * Nameless framework
 *
 * @package Nameless framework
 * @author  Corpsee <poisoncorpsee@gmail.com>
 * @license https://github.com/corpsee/nameless-source/blob/master/LICENSE
 * @link    https://github.com/corpsee/nameless-source
 */

namespace Nameless\Modules\Logger;

use Monolog\Logger as BaseLogger;
use Psr\Log\LoggerInterface;

/**
 * Logger class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Logger extends BaseLogger implements LoggerInterface
{
}