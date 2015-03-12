<?php

/**
 * Nameless framework
 *
 * @package Nameless framework
 * @author  Corpsee <poisoncorpsee@gmail.com>
 * @license https://github.com/corpsee/nameless-source/blob/master/LICENSE
 * @link    https://github.com/corpsee/nameless-source
 */

namespace Nameless\Modules\Validation;

use Nameless\Core\ModuleProvider as BaseModuleProvider;

/**
 * Validation ModuleProvider class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class ModuleProvider extends BaseModuleProvider
{
    public function register()
    {
        $this->container['validation.validator'] = function () {
            return new Validator();
        };
    }
}
