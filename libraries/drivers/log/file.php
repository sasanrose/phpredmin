<?php
class FileLog
{
    protected $_dir;

    public function __construct()
    {
        $config_dir = App::instance()->config['log']['file']['directory'];

        if (!$config_dir)
            die('Please provide a log directory in your config file');
        else {
            $this->_dir = dirname(__FILE__).'/../../../'.$config_dir.'/'.PHP_SAPI.'/';

            if (!is_writable($this->_dir))
                if (!mkdir($this->_dir, 0755, True))
                    die("{$this->_dir} does not exist or is not writable");
        }
    }

    public function write($type, $msg, $namespace = Null)
    {
        if (App::instance()->config['log']['threshold'] < Log::instance()->$type)
            return;

        $logfile = $this->_dir.date('Y-m-d').'.log';

        if (($file = fopen($logfile, 'a+')) === False)
            die('Can not open file: '.$logfile);

        $ip        = isset($_SERVER['REMOTE_ADDR']) ? "[{$_SERVER['REMOTE_ADDR']}]" : '';
        $namespace = isset($namespace) ? '['.ucwords(strtolower($namespace)).']' : '';
        $date      = '['.date('Y-m-d H:i:s').']';

        fwrite($file, "{$date} {$ip} {$namespace} [{$type}]: {$msg}\n");
        fclose($file);
    }
}
