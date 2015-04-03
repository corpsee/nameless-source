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
