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

class FileLog
{
    protected $_dir;
    protected $threshold;

    public function __construct()
    {
        $config_dir = App::instance()->config['log']['file']['directory'];

        $this->threshold = App::instance()->config['log']['threshold'];

        if ($this->threshold > 0) {
            if (!$config_dir) {
                die('Please provide a log directory in your config file');
            } else {
                $this->_dir = ROOT_DIR.'/'.$config_dir.'/'.PHP_SAPI.'/';

                if (!is_writable($this->_dir)) {
                    if (!mkdir($this->_dir, 0755, true)) {
                        die("{$this->_dir} does not exist or is not writable");
                    }
                }
            }
        }
    }

    public function write($type, $msg, $namespace = null)
    {
        if ($this->threshold < Log::instance()->$type) {
            return;
        }

        $logfile = $this->_dir.date('Y-m-d').'.log';

        if (($file = fopen($logfile, 'a+')) === false) {
            die('Can not open file: '.$logfile);
        }

        $ip        = isset($_SERVER['REMOTE_ADDR']) ? "[{$_SERVER['REMOTE_ADDR']}]" : '';
        $namespace = isset($namespace) ? '['.ucwords(strtolower($namespace)).']' : '';
        $date      = '['.date('Y-m-d H:i:s').']';

        fwrite($file, "{$date} {$ip} {$namespace} [{$type}]: {$msg}\n");
        fclose($file);
    }
}
