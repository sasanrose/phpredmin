<?php

class Redis_Helper
{
    protected static $_instance = Null;

    protected $db = Null;

    protected function __construct()
    {
        $this->db = Db::factory();
    }

    public static function instance()
    {
        if (!isset(self::$_instance))
            self::$_instance = new self;

        return self::$_instance;
    }

    public function getType($key)
    {
        switch ($this->db->type($key)) {
            case Redis::REDIS_STRING:
                return 'String';
            case Redis::REDIS_SET:
                return 'Set';
            case Redis::REDIS_LIST:
                return 'List';
            case Redis::REDIS_ZSET:
                return 'ZSet';
            case Redis::REDIS_HASH:
                return 'Hash';
            default:
                return '-';
        }
    }

    public function getTTL($key)
    {
        return $this->_time($this->db->ttl($key));

    }

    public function getIdleTime($key)
    {
        return $this->_time($this->db->object("idletime", $key));
    }

    public function getCount($key)
    {
        return $this->db->object("refcount", $key);
    }

    public function getEncoding($key)
    {
        return $this->db->object("encoding", $key);
    }

    public function getSize($key)
    {
        if (($size = $this->db->lSize($key)) === False)
            if (($size = $this->db->zCard($key)) === False)
                $size = '-';

        return $size <=0 ? '-' : $size;
    }

    protected function _time($time)
    {
        if ($time <= 0)
            return '-';
        else {
            $days = floor($time / 86400);

            return ($days > 0 ? "{$days} Days " : '') . gmdate('H:i:s', $time);
        }
    }
}
