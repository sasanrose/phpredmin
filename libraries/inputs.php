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

class inputs
{
    protected static $_instance = null;

    public static function instance()
    {
        if (!self::$_instance) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    public function input($key, $default = null)
    {
        switch (Router::instance()->method) {
            case Router::POST:
                $result = $this->post($key, $default);
                break;
            case Router::PUT:
                $result = $this->put($key, $default);
                break;
            case Router::GET:
                $result = $this->get($key, $default);
                break;
            default:
                $result = $default;
        }

        return $result;
    }

    public function post($key, $default = null)
    {
        if (isset($_POST[$key])) {
            if (is_array($_POST[$key])) {
                $results = array();

                foreach ($_POST[$key] as $index => $value) {
                    $results[$index] = filter_var($value, FILTER_SANITIZE_STRING);
                }

                return $results;
            } else {
                return filter_var($_POST[$key], FILTER_SANITIZE_STRING);
            }
        } else {
            return $default;
        }
    }

    public function get($key, $default = null)
    {
        $result = Router::instance()->query($key, $default);
        return $result ? $result : $default;
    }

    public function put($key, $default = null)
    {
        parse_str(file_get_contents('php://input'), $vars);
        return isset($vars[$key]) ? filter_var($vars[$key], FILTER_SANITIZE_STRING) : $default;
    }
}
