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

class PhpTemplate
{
    protected $_dir     = null;
    protected $_data    = array();
    protected $_headers = array();

    public $app    = null;
    public $router = null;

    public function __construct()
    {
        ini_set('output_buffering', 'On');
        ini_set('short_open_tag', 'On');

        $this->_dir   = '../views/';
        $this->app    = App::instance();
        $this->router = Router::instance();
    }

    public function render($__view, $data = array())
    {
        echo $this->renderPartial(App::instance()->config['default_layout'], array('content' => $this->renderPartial($__view, $data)));
    }

    public function renderPartial($__view, $__data = array())
    {
        $this->_data = array_merge($__data, $this->_data);

        ob_start();
        
        include($this->_dir.$__view.'.php');

        $content = ob_get_contents();

        ob_end_clean();

        return $content;
    }

    public function addHeader($header)
    {
        if (!in_array($header, $this->_headers)) {
            $this->_headers[] = $header;
        }
    }

    public function getHeaders()
    {
        return $this->_headers;
    }

    public function __set($key, $value)
    {
        $this->_data[$key] = $value;
    }

    public function __get($key)
    {
        return isset($this->_data[$key]) ? $this->_data[$key] : null;
    }

    public function __isset($key)
    {
        return isset($this->_data[$key]);
    }
}
