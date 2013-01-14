<?php
class Inputs
{
    protected static $_instance = Null;

    public static function instance()
    {
        if (!self::$_instance)
            self::$_instance = new self;

        return self::$_instance;
    }

    public function input($key, $default = Null)
    {
        switch (Router::instance()->method) {
            case Router::POST:
                $result = $this->post($key, $default);
                break;
            case Router::PUT:
                $result = $this->put($key, $default);
                break;
            case Router::GET:
                $result = $this->get($key, $default);
                break;
            default:
                $result = $default;
        }

        return $result;
    }

    public function post($key, $default = Null)
    {
        return isset($_POST[$key]) ? filter_var($_POST[$key], FILTER_SANITIZE_STRING) : $default;
    }

    public function get($key, $default = Null)
    {
        return filter_var(Router::instance()->query($key, $default), FILTER_SANITIZE_STRING);
    }

    public function put($key, $default = Null)
    {
        parse_str(file_get_contents("php://input"), $vars);
        return isset($vars[$key]) ? filter_var($vars[$key], FILTER_SANITIZE_STRING) : $default;
    }
}
