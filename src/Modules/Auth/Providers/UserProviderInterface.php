<?php

/**
 * Nameless framework
 *
 * @package Nameless framework
 * @author  Corpsee <poisoncorpsee@gmail.com>
 * @license https://github.com/corpsee/nameless-source/blob/master/LICENSE
 * @link    https://github.com/corpsee/nameless-source
 */

namespace Nameless\Modules\Auth\Providers;

/**
 * UserProviderInterface interface
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
Interface UserProviderInterface
{
    /**
     * @param string $user_name
     *
     * @return array|false
     */
    public function getUserByName($user_name);

    /**
     * @param string $user_name
     *
     * @return array|false
     */
    public function getUserGroups($user_name);
}
