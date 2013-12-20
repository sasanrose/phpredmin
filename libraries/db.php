<?php
final class Db
{
    protected static $_instances = Array();

    public static function factory($config, $driver = Null)
    {
        $driver = isset($driver) ? $driver : App::instance()->config['database']['driver'];

        $instanceName = $driver . ':' . $config['host'] . ':' . $config['port'];

        if (!isset(self::$_instances[$instanceName])) {
            include_once(App::instance()->drivers.'db/'.(strtolower($driver)).'.php');

            $class = ucwords(strtolower($driver)).'Db';
            self::$_instances[$instanceName] = new $class($config);
        }

        return self::$_instances[$instanceName];
    }
}
