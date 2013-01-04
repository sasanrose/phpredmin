<?php
final class Router
{
    const POST   = 'post';
    const GET    = 'get';
    const DELETE = 'delete';
    const PUT    = 'put';

    protected $_data          = Array();
    protected $_params        = Array();
    protected $_controller    = Null;
    protected $_action        = Null;
    protected $_query_strings = Array();

    protected static $_instance = Null;

    protected function __construct() {
        $this->parse();
    }

    public static function instance() {
        if (!self::$_instance)
            self::$_instance = new self;

        return self::$_instance;
    }

    protected function parse() {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->host   = $_SERVER['HTTP_HOST'];
        $this->path   = str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['REQUEST_URI']);

        if (preg_match('/^(.*)\?(.*)$/', $this->path, $matches)) {
            $this->path = $matches[1];

            foreach(explode('&', $matches[2]) as $match)
                if (preg_match('/^(.*)=(.*)$/', $match, $strings))
                    if ($strings[2])
                        $this->_query_strings[$strings[1]] = $strings[2];
        }

        $this->_params = explode('/', trim($this->path, '/'));

        if (!$this->_controller = ucwords(strtolower(array_shift($this->_params))))
            $this->_controller = App::instance()->config['default_controller'];

        if (!$this->_action = array_shift($this->_params))
            $this->_action = App::instance()->config['default_action'];
    }

    public function __get($key) {
        return isset($this->_data[$key]) ? $this->_data[$key] : Null;
    }

    public function __set($key, $value) {
        $this->_data[$key] = $value;
    }

    public function query($key, $default = Null) {
        return isset($this->_query_strings[$key]) ? $this->_query_strings[$key] : Null;
    }

    public function input($key, $default = Null) {
        switch ($this->method) {
            case self::POST:
                $result = isset($_POST[$key]) ? $_POST[$key] : $default;
                break;
            case self::PUT:
                parse_str(file_get_contents("php://input"), $vars);
                $result = isset($vars[$key]) ? $vars[$key] : $default;
                break;
            default:
                $result = $default;
        }

        return $result;
    }

    public function route() {
        $class      = $this->_controller.'_Controller';
        $method     = $this->_action.'Action';
        $controller = new $class;

        call_user_func_array(array($controller, $method), $this->_params);
    }
}
