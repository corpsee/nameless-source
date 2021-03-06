<?php

/**
 * Nameless framework
 *
 * @package Nameless framework
 * @author  Corpsee <poisoncorpsee@gmail.com>
 * @license https://github.com/corpsee/nameless-source/blob/master/LICENSE
 * @link    https://github.com/corpsee/nameless-source
 */

namespace Nameless\Core;

/**
 * Localization class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Localization
{
    /**
     * @var array
     */
    protected $files = [];

    /**
     * @var array
     */
    protected $lines = [];

    /**
     * @var string
     */
    protected $default_language;

    /**
     * @param string $default_language
     */
    public function __construct($default_language = 'en')
    {
        $this->default_language = $default_language;
    }

    //TODO: добавить обработку массивов в $file
    //TODO: добавить обработку абсолютных путей в $file
    /**
     * @param string $file
     * @param string $module
     * @param string $language
     * @param boolean $overwrite
     *
     * @throws \RuntimeException
     *
     * @return boolean
     */
    public function load($file, $module = 'application', $language = null, $overwrite = false)
    {
        if (!$overwrite && isset($this->files[$module][$language][$file])) {
            return $this;
        }

        $module = strtolower($module);
        switch ($module) {
            //TODO: ucfirst($module) for more than 1 word?
            case 'core':
                $file_path = dirname(__DIR__) . '/' . ucfirst($module) . '/localization/' . $language . '/' . $file . '.php';
                $default_file_path = dirname(__DIR__) . '/' . ucfirst($module) . '/localization/' . $this->default_language . '/' . $file . '.php';
                break;
            case 'application':
                $file_path = APPLICATION_PATH . 'localization/' . $language . '/' . $file . '.php';
                $default_file_path = APPLICATION_PATH . 'localization/' . $this->default_language . '/' . $file . '.php';
                break;
            default:
                $file_path = dirname(__DIR__) . '/Modules/' . ucfirst($module) . '/localization/' . $language . '/' . $file . '.php';
                $default_file_path = dirname(__DIR__) . '/Modules/' . ucfirst($module) . '/localization/' . $this->default_language . '/' . $file . '.php';
        }

        if (file_exists($file_path)) {
            if (!isset($this->lines[$language])) {
                $this->lines[$language] = [];
            }

            //TODO: correct include_once
            $lines = include $file_path;
            $this->lines[$language] = array_merge($this->lines[$language], $lines);

            return $language;
        } elseif (file_exists($default_file_path)) {
            if (!isset($this->lines[$this->default_language])) {
                $this->lines[$this->default_language] = [];
            }

            $lines = include $default_file_path;
            $this->lines[$this->default_language] = array_merge($this->lines[$this->default_language], $lines);

            return $this->default_language;
        } else {
            throw new \RuntimeException('Don`t find language file');
        }
    }

    /**
     * @param string $line_name
     * @param string $language
     * @param array $params
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public function get($line_name, $language = null, array $params = [])
    {
        $params_temp = [];
        foreach ($params as $param_name => $param) {
            $params_temp[':' . $param_name . ':'] = $param;
        }
        unset($param);

        if (isset($this->lines[$language][$line_name])) {
            return strtr($this->lines[$language][$line_name], $params_temp);
        } elseif (isset($this->lines[$this->default_language][$line_name])) {
            return strtr($this->lines[$this->default_language][$line_name], $params_temp);
        } else {
            throw new \RuntimeException('Don`t find language line');
        }
    }
}
