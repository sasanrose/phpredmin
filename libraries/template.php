<?php
final class Template
{
    protected static $_instances = Array();

    public static function factory($driver = 'php') {
        if (!isset(self::$_instances[$driver])) {
            $class = ucwords(strtolower($driver)).'Template';
            self::$_instances[$driver] = new $class;
        }

        return self::$_instances[$driver];
    }
}
