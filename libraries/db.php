<?php
final class Db
{
    protected static $_instances = Array();

    public static function factory($driver = Null) {
        if (!isset(self::$_instances[$driver])) {
            $driver = isset($driver) ? $driver : App::instance()->config['database']['driver'];

            include_once(App::instance()->drivers.'db'.DIRECTORY_SEPARATOR.(strtolower($driver)).'.php');

            $class  = ucwords(strtolower($driver)).'Db';
            self::$_instances[$driver] = new $class;
        }

        return self::$_instances[$driver];
    }
}
