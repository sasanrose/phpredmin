<?php
class RedisDb extends Redis
{
    public function __construct() {
        $config = App::instance()->config;

        $this->connect($config['database']['redis']['host'], $config['database']['redis']['port']);
        $this->select($config['database']['redis']['database']);

        if (isset($config['database']['redis']['password']))
            $this->auth($config['database']['redis']['password']);
    }

}
