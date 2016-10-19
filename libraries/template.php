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

final class template
{
    protected static $_instances = array();

    public static function factory($driver = 'php')
    {
        ini_set('short_open_tag', 'On');

        if (!isset(self::$_instances[$driver])) {
            include_once(App::instance()->drivers.'template/'.(strtolower($driver)).'.php');

            $class  = ucwords(strtolower($driver)).'Template';
            self::$_instances[$driver] = new $class;
        }

        return self::$_instances[$driver];
    }
}
