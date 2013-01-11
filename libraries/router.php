<?php
final class Router
{
    const POST   = 'post';
    const GET    = 'get';
    const DELETE = 'delete';
    const PUT    = 'put';

    protected $_data          = Array();
    protected $_params        = Array();
    protected $_query_strings = Array();

    protected static $_instance = Null;

    protected function __construct()
    {
        $this->parse();
    }

    public static function instance()
    {
        if (!self::$_instance)
            self::$_instance = new self;

        return self::$_instance;
    }

    protected function parse()
    {
        $this->method   = $_SERVER['REQUEST_METHOD'];
        $this->protocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';
        $this->host     = $_SERVER['HTTP_HOST'];
        $this->baseUrl  = $this->protocol.'://'.$this->host;
        $this->url      = $this->protocol.'://'.$this->host.$_SERVER['SCRIPT_NAME'];
        $this->path     = '';

        if (PHP_SAPI != 'cli') {
            $this->path = str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['REQUEST_URI']);
            Log::factory()->write(Log::INFO, $_SERVER['REQUEST_URI'], 'Router');

            if ($this->path == $_SERVER['REQUEST_URI'])
                $this->path = '';
        } else if (isset($_SERVER['argv'][1]))
                $this->path = $_SERVER['argv'][1];

        if (preg_match('/^(.*)\/(.*)$/', $_SERVER['SCRIPT_NAME'], $matches))
            $this->baseUrl .= $matches[1];

        if (preg_match('/^(.*)\?(.*)$/', $this->path, $matches)) {
            $this->path = $matches[1];

            foreach(explode('&', $matches[2]) as $match)
                if (preg_match('/^(.*)=(.*)$/', $match, $strings))
                    if ($strings[2])
                        $this->_query_strings[$strings[1]] = $strings[2];
        }

        $this->_params = explode('/', trim($this->path, '/'));

        if (!$this->controller = ucwords(strtolower(array_shift($this->_params))))
            $this->controller = App::instance()->config['default_controller'];

        if (!$this->action = array_shift($this->_params))
            $this->action = App::instance()->config['default_action'];
    }

    public function __get($key)
    {
        return isset($this->_data[$key]) ? $this->_data[$key] : Null;
    }

    public function __set($key, $value)
    {
        $this->_data[$key] = $value;
    }

    public function query($key, $default = Null)
    {
        return isset($this->_query_strings[$key]) ? filter_var($this->_query_strings[$key], FILTER_SANITIZE_STRING) : Null;
    }

    public function route()
    {
        $class  = $this->controller.'_Controller';
        $method = $this->action.'Action';

        if (class_exists($class)) {
            $controller = new $class;
            if (method_exists($controller, $method))
                call_user_func_array(array($controller, $method), $this->_params);

            return;
        }

        header("HTTP/1.0 404 Not Found");
        Template::factory()->render('404');
    }
}
