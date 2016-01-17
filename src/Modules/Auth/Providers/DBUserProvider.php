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

use Nameless\Modules\Database;
use Nameless\Utilities\StringHelper;

/**
 * DBUserProvider class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class DBUserProvider implements UserProviderInterface
{
    /**
     * @var Database
     */
    private $database;

    /**
     * @param Database $database
     */
    public function  __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * @param string $user_name
     *
     * @return array|false
     */
    public function getUserByName($user_name)
    {
        return $this->database->selectOne("SELECT `id`, `password` FROM `users` WHERE `username` = ?", [$user_name]);
    }

    /**
     * @param string $user_name
     *
     * @return array|false
     */
    public function getUserGroups($user_name)
    {
        $groups = $this->database->selectOne("SELECT `groups` FROM `users` WHERE `username` = ?", [$user_name]);

        if ($groups) {
            return StringHelper::toArray($groups['groups']);
        }
        return false;
    }
}
