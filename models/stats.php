<?php

class Stats_Model extends Model
{
    const STATS_MODEL_KEY = 'phpredmin:stats:';
    
    public function resetStats()
    {
        $this->db->changeDB($this->app->current['stats']['database']);
        
        $keys = $this->db->keys(self::STATS_MODEL_KEY . '*');
        
        foreach($keys as $key) {
            $this->db->del($key);
        }       
        
        $this->db->changeDB($this->app->current['database']);
    }

    public function addKey($key, $value, $time)
    {
        $this->db->changeDB($this->app->current['stats']['database']);
        
        // add value with timestamp prefix to make it unique
        // in other case non-unique value won't be added
        // @see http://redis.io/commands/zadd
        $this->db->zAdd(self::STATS_MODEL_KEY . $key, $time, $time . ':' . $value);
        
        $this->db->changeDB($this->app->current['database']);
    }

    public function getKeys($key, $from, $to)
    {
        $this->db->changeDB($this->app->current['stats']['database']);
        
        $results = Array();
        $keys = $this->db->zRevRangeByScore(self::STATS_MODEL_KEY . $key, $to, $from, Array('withscores' => True));

        foreach ($keys as $value => $time) {
            $value = explode(':', $value);
            $results[] = array($time, (float)$value[1]);
        }

        $this->db->changeDB($this->app->current['database']);
        
        return $results;
    }
}
