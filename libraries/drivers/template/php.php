<?php
class PhpTemplate
{
    protected $_dir     = Null;
    protected $_data    = Array();
    protected $_headers = Array();

    public $app    = Null;
    public $router = Null;

    public function __construct()
    {
        $this->_dir   = '../views/';
        $this->app    = App::instance();
        $this->router = Router::instance();
    }

    public function render($__view, $data = Array())
    {
        $content = $this->renderPartial($__view, $data);

        echo $this->renderPartial(App::instance()->config['default_layout'], array('content' => $content));
    }

    public function renderPartial($__view, $__data = Array())
    {
        $this->_data = array_merge($__data, $this->_data);

        ob_start();
        
        include($this->_dir.$__view.'.php');

        $content = ob_get_contents();

        ob_clean();

        return $content;
    }

    public function addHeader($header)
    {
        if (!in_array($header, $this->_headers))
            $this->_headers[] = $header;
    }

    public function getHeaders($header)
    {
        return $this->_headers;
    }

    public function __set($key, $value)
    {
        $this->_data[$key] = $value;
    }

    public function __get($key)
    {
        return isset($this->_data[$key]) ? $this->_data[$key] : Null;
    }

    public function __isset($key)
    {
        return isset($this->_data[$key]);
    }
}
