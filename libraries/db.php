<?php
final class Db
{
    protected static $_instances = Array();

    public static function factory($driver = Null, $config)
    {
        $driver = isset($driver) ? $driver : App::instance()->config['database']['driver'];

        if (!isset(self::$_instances[$driver])) {
            include_once(App::instance()->drivers.'db/'.(strtolower($driver)).'.php');

            $class = ucwords(strtolower($driver)).'Db';
            self::$_instances[$driver] = new $class($config);
        }

        return self::$_instances[$driver];
    }
}
