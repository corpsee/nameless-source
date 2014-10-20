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

namespace Nameless\Modules\Validation;

use Pimple\Container;

/**
 * Validator class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
//TODO: validator refactoring!
class Validator
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @param string $value
     *
     * @return boolean
     */
    private function noempty($value)
    {
        if (!$value) {
            return false;
        }
        return true;
    }

    /**
     * @param string $value
     *
     * @return boolean
     */
    private function number($value)
    {
        if ($value && !preg_match('!^[\p{Nd}]+$!iu', $value)) {
            return false;
        }
        return true;
    }

    /**
     * @param string $value
     *
     * @return boolean
     */
    private function decimal($value)
    {
        if ($value && !preg_match('!^[\p{Nd}\., ]+$!iu', $value)) {
            return false;
        }
        return true;
    }

    /**
     * @param string $value
     *
     * @return boolean
     */
    private function alpha($value)
    {
        if ($value && !preg_match('!^[\p{L} ]+$!iu', $value)) {
            return false;
        }
        return true;
    }

    /**
     * @param string $value
     *
     * @return boolean
     */
    private function alpha_ext($value)
    {
        if ($value && !preg_match('!^[\p{L}\p{P}\p{Nd} ]+$!iu', $value)) {
            return false;
        }
        return true;
    }

    /**
     * @param string $value
     *
     * @return boolean
     */
    private function email_simple($value)
    {
        if ($value && !preg_match('!^[^@]*@[^@]*$!iu', $value)) {
            return false;
        }
        return true;
    }

    /**
     * @param string $value
     *
     * @return boolean
     */
    private function email($value)
    {
        if ($value && !preg_match(
                '!
                    ^[a-zA-Z0-9][-_+\.a-zA-Z0-9]*                      # имя почты (может включать .-+_)
                    @                                    # разделитель
                    [\p{L}\p{Nd}][-\p{L}\p{Nd}]*         # домменое имя без зоны (любое кол-во поддоменов)
                    ([\.][\p{L}\p{Nd}][-\p{L}\p{Nd}]*)?$ # доменная зона
                !xiu',
                $value
            )
        ) {
            return false;
        }
        return true;
    }

    /**
     * @param string $value
     *
     * @return boolean
     */
    private function phone($value)
    {
        if ($value && !preg_match('!^[\p{Nd}+\(\) ]+$!iu', $value)) {
            return false;
        }
        return true;
    }

    /**
     * @param string $value
     *
     * @return boolean
     */
    private function url($value)
    {
        if ($value && !preg_match(
                '!^(http://|https://){0,1}(www\.){0,1}([-\p{L}\p{Nd}_]{2,}){1,}\.[\p{L}\p{Nd}]{2,4}[-\p{L}\p{Nd}_+&/=?\.%]{1,}!iu',
                $value
            )
        ) {
            return false;
        }
        return true;
    }

    /**
     * @param string $value
     * @param string $rule
     *
     * @return boolean
     */
    private function length($value, $rule)
    {
        if (mb_strlen($value, 'UTF-8') !== $rule) {
            return false;
        }
        return true;
    }

    /**
     * @param string $value
     * @param string $rule
     *
     * @return boolean
     */
    private function min_length($value, $rule)
    {
        if (mb_strlen($value, 'UTF-8') < $rule) {
            return false;
        }
        return true;
    }

    /**
     * @param string $value
     * @param string $rule
     *
     * @return boolean
     */
    private function max_length($value, $rule)
    {
        if (mb_strlen($value, 'UTF-8') > $rule) {
            return false;
        }
        return true;
    }

    /**
     * @param string $value
     * @param string $rule
     *
     * @return boolean
     */
    private function equal_field($value, $rule)
    {
        if ($value !== $rule) {
            return false;
        }
        return true;
    }

    //TODO: private
    /**
     * @param string $key
     * @param string $value
     * @param array $rules
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function validateField($key, $value, $rules)
    {
        $errors = [];
        $value = trim($value);

        foreach ($rules as $rule) {
            if (is_array($rule) && count($rule) == 2) {
                if (!$error = $this->{$rule[0]}($value)) {
                    $errors[] = $this->container['localization']->get(
                        $rule[0],
                        ['field' => $key, 'param2' => $rule[1]]
                    );
                }
            } elseif (is_string($rule)) {
                if (!$error = $this->{$rule}($value)) {
                    $errors[] = $this->container['localization']->get($rule, ['field' => $key]);
                }
            } else {
                throw new \InvalidArgumentException('Invalid validation rule');
            }
        }

        return $errors;
    }

    public function validateFieldTest($value, $rules)
    {
        $errors = [];
        $value = trim($value);

        foreach ($rules as $rule) {
            if (is_array($rule) && count($rule) == 2) {
                if (!$error = $this->{$rule[0]}($value)) {
                    $errors[] = 'error';
                }
            } elseif (is_string($rule)) {
                if (!$error = $this->{$rule}($value)) {
                    $errors[] = 'error';
                }
            } else {
                throw new \InvalidArgumentException('Invalid validation rule');
            }
        }

        return $errors;
    }

    /**
     * @param string $form
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function validate($form)
    {
        $errors = [];
        if (isset($this->container['validation.rules'][$form])) {
            $post = $this->container['request']->request->all();
            foreach ($post as $key => $value) {
                if (isset($this->container['validation.rules'][$form][$key])) {
                    if ($validate = $this->validateField(
                        $key,
                        $value,
                        $this->container['validation.rules'][$form][$key]
                    )
                    ) {
                        $errors[] = $validate;
                    }
                }
            }
        } else {
            throw new \InvalidArgumentException('Invalid form name');
        }
        return $errors;
    }
}