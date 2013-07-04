<?php

/**
 * This file is part of the Nameless framework.
 * For the full copyright and license information, please view the LICENSE
 *
 * @package    Nameless
 * @author     Corpsee <poisoncorpsee@gmail.com>
 * @copyright  2012 - 2013. Corpsee <poisoncorpsee@gmail.com>
 * @link       https://github.com/corpsee/Nameless
 */

namespace Nameless\Modules\Logger;

use Monolog\Logger as BaseLogger;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

/**
 * Logger class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Logger extends BaseLogger implements LoggerInterface {}