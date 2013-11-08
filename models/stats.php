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
        
        $this->db->zAdd(self::STATS_MODEL_KEY . $key, $time, $value);
        
        $this->db->changeDB($this->app->current['database']);
    }

    public function getKeys($key, $from, $to)
    {
        $this->db->changeDB($this->app->current['stats']['database']);
        
        $results = Array();
        $keys = $this->db->zRevRangeByScore(self::STATS_MODEL_KEY . $key, $to, $from, Array('withscores' => True));

        foreach ($keys as $key => $value) {
            $results[] = array($value, $key);
        }

        $this->db->changeDB($this->app->current['database']);
        
        return $results;
    }
}
