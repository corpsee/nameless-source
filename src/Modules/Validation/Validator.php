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

/**
 * Validator class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Validator
{
    /**
     * Config rule: no_empty
     *
     * @param string $value
     *
     * @return boolean
     */
    protected function noEmpty($value)
    {
        if (!$value) {
            return false;
        }
        return true;
    }

    /**
     * Config rule: number
     *
     * @param string $value
     *
     * @return boolean
     */
    protected function number($value)
    {
        if ($value && !preg_match('!^[\p{Nd}]+$!iu', $value)) {
            return false;
        }
        return true;
    }

    /**
     * Config rule: decimal
     *
     * @param string $value
     *
     * @return boolean
     */
    protected function decimal($value)
    {
        if ($value && !preg_match('!^[\p{Nd}\., ]+$!iu', $value)) {
            return false;
        }
        return true;
    }

    /**
     * Config rule: alpha
     *
     * @param string $value
     *
     * @return boolean
     */
    protected function alpha($value)
    {
        if ($value && !preg_match('!^[\p{L} ]+$!iu', $value)) {
            return false;
        }
        return true;
    }

    /**
     * Config rule: alpha_ext
     *
     * @param string $value
     *
     * @return boolean
     */
    protected function alphaExt($value)
    {
        if ($value && !preg_match('!^[\p{L}\p{P}\p{Nd} ]+$!iu', $value)) {
            return false;
        }
        return true;
    }

    /**
     * Config rule: email_simple
     *
     * @param string $value
     *
     * @return boolean
     */
    protected function emailSimple($value)
    {
        if ($value && !preg_match('!^[^@]*@[^@]*$!iu', $value)) {
            return false;
        }
        return true;
    }

    /**
     * Config rule: email
     *
     * @param string $value
     *
     * @return boolean
     */
    protected function email($value)
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
     * Config rule: phone
     *
     * @param string $value
     *
     * @return boolean
     */
    protected function phone($value)
    {
        if ($value && !preg_match('!^[\p{Nd}+\(\) ]+$!iu', $value)) {
            return false;
        }
        return true;
    }

    /**
     * Config rule: url
     *
     * @param string $value
     *
     * @return boolean
     */
    protected function url($value)
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
     * Config rule: length
     *
     * @param string  $value
     * @param integer $rule
     *
     * @return boolean
     */
    protected function length($value, $rule)
    {
        if (mb_strlen($value, 'UTF-8') !== $rule) {
            return false;
        }
        return true;
    }

    /**
     * Config rule: min_length
     *
     * @param string  $value
     * @param integer $rule
     *
     * @return boolean
     */
    protected function minLength($value, $rule)
    {
        if (mb_strlen($value, 'UTF-8') < $rule) {
            return false;
        }
        return true;
    }

    /**
     * Config rule: max_length
     *
     * @param string  $value
     * @param integer $rule
     *
     * @return boolean
     */
    protected function maxLength($value, $rule)
    {
        if (mb_strlen($value, 'UTF-8') > $rule) {
            return false;
        }
        return true;
    }

    /**
     * Config rule: equal_field
     *
     * @param string $value
     * @param string $rule
     *
     * @return boolean
     */
    protected function equalField($value, $rule)
    {
        if ($value !== $rule) {
            return false;
        }
        return true;
    }

    /**
     * @param string         $rule          Config rule: no_empty|number|decimal|alpha|alpha_ext|email_simple|email|phone|url|length|min_length|max_length|equal_field
     * @param mixed          $value
     * @param integer|string $rule_extended
     *
     * @throws \InvalidArgumentException
     *
     * @return boolean
     */
    protected function checkRule($rule, $value, $rule_extended = null)
    {
        switch ($rule) {
            case 'no_empty':
                return $this->noEmpty($value);
            case 'number':
                return $this->number($value);
            case 'decimal':
                return $this->decimal($value);
            case 'alpha':
                return $this->alpha($value);
            case 'alpha_ext':
                return $this->alphaExt($value);
            case 'email_simple':
                return $this->emailSimple($value);
            case 'email':
                return $this->email($value);
            case 'phone':
                return $this->phone($value);
            case 'url':
                return $this->url($value);
            case 'length':
                return $this->length($value, (integer)$rule_extended);
            case 'min_length':
                return $this->minLength($value, (integer)$rule_extended);
            case 'max_length':
                return $this->maxLength($value, (integer)$rule_extended);
            case 'equal_field':
                return $this->equalField($value, $rule_extended);
            default:
                throw new \InvalidArgumentException('Invalid validation rule');
        }
    }

    /**
     * @param string $value
     * @param array  $rules
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    public function validate($value, array $rules)
    {
        $errors = [];
        $value  = trim($value);

        foreach ($rules as $rule) {
            list($rule_normalized, $rule_extended) = [$rule, null];
            if (is_array($rule)) {
                list($rule_normalized, $rule_extended) = $rule;
            }

            if (!$this->checkRule($rule_normalized, $value, $rule_extended)) {
                $errors[] = $rule[0];
            }
        }

        return $errors;
    }
}
