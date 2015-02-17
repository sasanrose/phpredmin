<?php
class RedisDb extends Redis
{
    public function __construct($config)
    {
        $this->connect($config['host'], $config['port']);
        $this->select($config['database']);

        if (isset($config['password'])) {
            $this->auth($config['password']);
        }
    }

    public function changeDB($db)
    {
        $this->select($db);

        return $this;
    }
}
