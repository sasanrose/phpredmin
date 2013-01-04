<?php
class PhpTemplate
{
    protected $_dir  = Null;
    protected $_data = Array();

    public function __construct() {
        $this->_dir  = '..'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR;
    }

    public function render($__view, $data = Array()) {
        $content = $this->renderPartial($__view, $data);

        echo $this->renderPartial(App::instance()->config['default_layout'], array('content' => $content));
    }

    public function renderPartial($__view, $__data = Array()) {
        $this->_data = array_merge($__data, $this->_data);

        ob_start();
        
        include($this->_dir.$__view.'.php');

        $content = ob_get_contents();

        ob_clean();

        return $content;
    }

    public function __set($key, $value) {
        $this->_data[$key] = $value;
    }

    public function __get($key) {
        return isset($this->_data[$key]) ? $this->_data[$key] : Null;
    }
}
