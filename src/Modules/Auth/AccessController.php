<?php

/**
 * This file is part of the Nameless framework.
 * For the full copyright and license information, please view the LICENSE
 *
 * @package    Nameless
 * @author     Corpsee <poisoncorpsee@gmail.com>
 * @copyright  2012 - 2014. Corpsee <poisoncorpsee@gmail.com>
 * @link       https://github.com/corpsee/Nameless
 */

namespace Nameless\Modules\Auth;

use Symfony\Component\HttpFoundation\Response;
use Nameless\Core\Controller;

/**
 * AccessController class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class AccessController extends Controller
{
    public function before()
    {

        $access = $this->container['auth.user']->getAccessByRoute($this->getAttributes('_route'));

        if (!$access) {
            throw new AccessDeniedException('Access Denied!');
        }
    }
}