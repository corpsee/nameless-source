<?php

/**
 * Nameless framework
 *
 * @package Nameless framework
 * @author  Corpsee <poisoncorpsee@gmail.com>
 * @license https://github.com/corpsee/nameless-source/blob/master/LICENSE
 * @link    https://github.com/corpsee/nameless-source
 */

namespace Nameless\Modules\Auth;

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
