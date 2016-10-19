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

class model
{
    private $_objects = null;

    public function __construct($config)
    {
        $this->_objects['app']     = App::instance();
        $this->_objects['router']  = Router::instance();
        $this->_objects['session'] = Session::instance();
        $this->_objects['db']      = Db::factory($config);
        $this->_objects['log']     = Log::factory();
    }

    public function __get($object)
    {
        return isset($this->_objects[$object]) ? $this->_objects[$object] : null;
    }
}
