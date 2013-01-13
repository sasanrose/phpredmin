<?php

class Stats_Model extends Model
{
    public function resetStats($info)
    {
        $this->db->del('phpredmin:memory');
        $this->db->del('phpredmin:connections');
        $this->db->del('phpredmin:commands');
        $this->db->del('phpredmin:expired_keys');
        $this->db->del('phpredmin:hits');
        $this->db->del('phpredmin:misses');
        $this->db->del('phpredmin:clients');
        $this->db->del('phpredmin:user_cpu');
        $this->db->del('phpredmin:system_cpu');

        foreach ($this->infoModel->getDbs($info) as $db)
            if (preg_match('/^keys=([0-9]+),expires=([0-9]+)$/', $info["db{$db}"], $matches)) {
                $this->db->del("phpredmin:db{$db}:keys");
                $this->db->del("phpredmin:db{$db}:expired_keys");
            }
    }

    public function addKey($key, $value, $time)
    {
        $this->db->zAdd("phpredmin:{$key}", $time, $value);
    }

    public function getKeys($key, $from, $to)
    {
        $results = Array();
        $keys    = $this->db->zRevRangeByScore("phpredmin:{$key}", $to, $from, Array('withscores' => True));

        foreach ($keys as $key => $value) {
            $results[] = array($value, $key);
        }

        return $results;
    }
}
