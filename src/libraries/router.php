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

final class Router
{
    const POST   = 'POST';
    const GET    = 'GET';
    const DELETE = 'DELETE';
    const PUT    = 'PUT';

    protected $_data          = array();
    protected $_params        = array();
    protected $_query_strings = array();

    protected static $_instance = null;

    protected function __construct()
    {
        $this->parse();
    }

    public static function instance()
    {
        if (!self::$_instance) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    protected function parse()
    {
        $this->method   = $_SERVER['REQUEST_METHOD'];
        $this->protocol = (
            (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
        ) ? 'https' : 'http';
        $this->host     = $_SERVER['HTTP_HOST'];
        $this->baseUrl  = $this->protocol.'://'.$this->host;
        $this->url      = $this->protocol.'://'.$this->host.$_SERVER['SCRIPT_NAME'];
        $this->path     = '';

        if (PHP_SAPI != 'cli') {
            $this->path = str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['REQUEST_URI']);
            Log::factory()->write(Log::INFO, $_SERVER['REQUEST_URI'], 'Router');

            if ($this->path == $_SERVER['REQUEST_URI']) {
                $this->path = '';
            }
        } elseif (isset($_SERVER['argv'][1])) {
            $this->path = $_SERVER['argv'][1];
        }

        $this->request = $this->url.$this->path;

        if (preg_match('/^(.*)\/(.*)$/', $_SERVER['SCRIPT_NAME'], $matches)) {
            $this->baseUrl .= $matches[1];
        }

        if (preg_match('/^(.*)\?(.*)$/', $this->path, $matches)) {
            $this->path = $matches[1];

            foreach (explode('&', $matches[2]) as $match) {
                if (preg_match('/^(.*)=(.*)$/', $match, $strings)) {
                    if ($strings[2]) {
                        $this->_query_strings[$strings[1]] = urldecode($strings[2]);
                    }
                }
            }
        }

        $this->_params = explode('/', trim($this->path, '/'));

        if (!$this->controller = ucwords(strtolower(array_shift($this->_params)))) {
            $this->controller = App::instance()->config['default_controller'];
        }

        if (!$this->action = array_shift($this->_params)) {
            $this->action = App::instance()->config['default_action'];
        }

        if (!$this->serverId = array_shift($this->_params)) {
            $this->serverId = 0;
        }

        if (!$this->dbId = array_shift($this->_params)) {
            $this->dbId = 0;
        }
    }

    public function __get($key)
    {
        return isset($this->_data[$key]) ? $this->_data[$key] : null;
    }

    public function __set($key, $value)
    {
        $this->_data[$key] = $value;
    }

    public function query($key, $default = null)
    {
        return isset($this->_query_strings[$key]) ? filter_var($this->_query_strings[$key], FILTER_SANITIZE_STRING) : $default;
    }

    public function route()
    {
        $class  = $this->controller.'_Controller';
        $method = $this->action.'Action';

        if (class_exists($class)) {
            $controller = new $class(
                array(
                    'serverId' => $this->serverId,
                    'dbId' => $this->dbId,
                )
            );
            if (method_exists($controller, $method)) {
                call_user_func_array(array($controller, $method), $this->_params);
            }

            return;
        }

        header('HTTP/1.0 404 Not Found');
        Template::factory()->render('404');
    }

    public function redirect($url)
    {
        header("Location: {$this->url}/{$url}");
        exit;
    }
}
