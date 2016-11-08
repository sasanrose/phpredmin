<?php

/*
 * This file is part of the "PHP Redis Admin" package.
 *
 * (c) Faktiva (http://faktiva.com)
 *
 * NOTICE OF LICENSE
 * This source file is subject to the CC BY-SA 4.0 license that is
 * available at the URL https://creativecommons.org/licenses/by-sa/4.0/
 *
 * DISCLAIMER
 * This code is provided as is without any warranty.
 * No promise of being safe or secure
 *
 * @author   Sasan Rose <sasan.rose@gmail.com>
 * @author   Emiliano 'AlberT' Gabrielli <albert@faktiva.com>
 * @license  https://creativecommons.org/licenses/by-sa/4.0/  CC-BY-SA-4.0
 * @source   https://github.com/faktiva/php-redis-admin
 */

final class App
{
    protected static $_instance = null;

    protected $_data = array();
    protected $_config_dir = '';

    protected function __construct()
    {
        $this->_config_dir = ROOT_DIR.'/app/config';
        $this->_data['config'] = require_once file_exists($this->_config_dir.'/config.php') ? $this->_config_dir.'/config.php' : $this->_config_dir.'/config.dist.php';
        $this->_data['drivers'] = 'drivers/';

        $this->readEnvConfig();
    }

    public static function instance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __get($key)
    {
        return isset($this->_data[$key]) ? $this->_data[$key] : null;
    }

    public function __set($key, $value)
    {
        $this->_data[$key] = $value;
    }

    protected function readEnvConfig()
    {
        $envConf = preg_grep('/^PHPREDMIN_/', array_keys($_ENV));

        if (!empty($envConf)) {
            foreach ($envConf as $conf) {
                $keys = explode('_', $conf);

                if (!empty($keys)) {
                    array_shift($keys);

                    self::setConfig($this->_data['config'], $keys, $_ENV[$conf]);
                }
            }
        }
    }

    protected static function setConfig(&$config, $keys, $value)
    {
        $key = array_shift($keys);

        $key = strtolower($key);

        if (isset($config[$key])) {
            if (is_array($config[$key])) {
                return self::setConfig($config[$key], $keys, $value);
            } else {
                $config[$key] = $value;

                return true;
            }
        }

        return false;
    }
}
