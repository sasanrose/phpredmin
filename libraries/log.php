<?php

final class Log
{
    protected static $_instances = Array();

    const INFO    = 'info';
    const DEBUG   = 'debug';
    const ERROR   = 'error';
    const WARNING = 'warning';
    const NOTICE  = 'notice';

    public static function factory($driver = Null)
    {
        if (!isset(self::$_instances[$driver])) {
            $driver = isset($driver) ? $driver : App::instance()->config['log']['driver'];

            include_once(App::instance()->drivers.'log/'.(strtolower($driver)).'.php');

            $class  = ucwords(strtolower($driver)).'Log';
            self::$_instances[$driver] = new $class;
        }

        return self::$_instances[$driver];
    }
}
