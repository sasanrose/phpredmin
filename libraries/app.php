<?php
final class App
{
    protected static $_instance = Null;

    protected $_data = Array();

    protected function __construct() {
        $this->_data['config'] = include_once('..'.DIRECTORY_SEPARATOR.'config.php');
    }

    public static function instance() {
        if (!self::$_instance)
            self::$_instance = new self;

        return self::$_instance;
    }

    public function __get($key) {
        return isset($this->_data[$key]) ? $this->_data[$key] : Null;
    }
}
