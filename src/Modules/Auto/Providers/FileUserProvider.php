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

namespace Nameless\Modules\Auto\Providers;

use Nameless\Modules\Auto\User;

/**
 * FileUserProvider class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class FileUserProvider implements UserProviderInterface
{
    /**
     * @var array
     */
    private $users;

    /**
     * @param array $users
     */
    public function  __construct(array $users)
    {
        $this->users = $users;
    }

    /**
     * @param string $user_name
     *
     * @return array|false
     */
    public function getUserByName($user_name)
    {
        if (isset($this->users[$user_name])) {
            $user = $this->users[$user_name];
            $user['username'] = $user_name;
            unset($user['groups']);

            return $user;
        } else {
            return false;
        }
    }

    /**
     * @param string $user_name
     *
     * @return array|false
     */
    public function getUserGroups($user_name)
    {
        if (isset($this->users[$user_name])) {
            return $this->users[$user_name]['groups'];
        } else {
            return false;
        }
    }
}