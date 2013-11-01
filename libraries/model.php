<?php
class Model
{
    private $_objects = Null;

    public function __construct($config)
    {
        $this->_objects['app']     = App::instance();
        $this->_objects['router']  = Router::instance();
        $this->_objects['session'] = Session::instance();
        $this->_objects['db']      = Db::factory(Null, $config);
        $this->_objects['log']     = Log::factory();
    }

    public function __get($object)
    {
        return isset($this->_objects[$object]) ? $this->_objects[$object] : Null;
    }
}
