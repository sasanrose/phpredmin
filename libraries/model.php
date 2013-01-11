<?php
class Model
{
    private $_objects = Null;

    public function __construct()
    {
        $this->_objects['app']    = App::instance();
        $this->_objects['router'] = Router::instance();
        $this->_objects['db']     = Db::factory();
        $this->_objects['log']    = Log::factory();
    }

    public function __get($object)
    {
        return isset($this->_objects[$object]) ? $this->_objects[$object] : Null;
    }
}
