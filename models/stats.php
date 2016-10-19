<?php

/*
 * This file is part of the "PHP Redis Admin" package.
 *
 * (c) Faktiva (http://faktiva.com)
 *
 * NOTICE OF LICENSE
 * This source file is subject to the CC BY-SA 4.0 license that is
 * available at the URL https://creativecommons.org/licenses/by-sa/4.0/
 *
 * DISCLAIMER
 * This code is provided as is without any warranty.
 * No promise of being safe or secure
 *
 * @author   Sasan Rose <sasan.rose@gmail.com>
 * @author   Emiliano 'AlberT' Gabrielli <albert@faktiva.com>
 * @license  https://creativecommons.org/licenses/by-sa/4.0/  CC-BY-SA-4.0
 * @source   https://github.com/faktiva/php-redis-admin
 */

class Stats_Model extends Model
{
    const STATS_MODEL_KEY = 'phpredmin:stats:';
    
    /**
     * Server for Stats model could be different from current server for app.
     * For example in cron.
     */
    private $currentServer;
    
    public function __construct($config)
    {
        parent::__construct($config);
        
        $this->currentServer = $config;
    }
    
    public function resetStats()
    {
        $this->db->changeDB($this->currentServer['stats']['database']);
        
        $keys = $this->db->keys(self::STATS_MODEL_KEY . '*');
        
        foreach ($keys as $key) {
            $this->db->del($key);
        }
        
        $this->db->changeDB($this->currentServer['database']);
    }

    public function addKey($key, $value, $time)
    {
        $this->db->changeDB($this->currentServer['stats']['database']);
        
        // add value with timestamp prefix to make it unique
        // in other case non-unique value won't be added
        // @see http://redis.io/commands/zadd
        $this->db->zAdd(self::STATS_MODEL_KEY . $key, $time, $time . ':' . $value);
        
        $this->db->changeDB($this->currentServer['database']);
    }

    public function getKeys($key, $from, $to)
    {
        $this->db->changeDB($this->currentServer['stats']['database']);
        
        $results = array();
        $keys = $this->db->zRevRangeByScore(self::STATS_MODEL_KEY . $key, $to, $from, array('withscores' => true));

        foreach ($keys as $value => $time) {
            $value = explode(':', $value);
            $results[] = array($time, (float)$value[1]);
        }

        $this->db->changeDB($this->currentServer['database']);
        
        return $results;
    }
}
