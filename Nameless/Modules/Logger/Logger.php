<?php

/*
 * This file is part of the Nameless framework package.
 * (c) Corpsee <poisoncorpsee@gmail.com>
 */
namespace Nameless\Modules\Logger;

use Monolog\Logger as BaseLogger;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

/**
 * Logger
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Logger extends BaseLogger implements LoggerInterface {}