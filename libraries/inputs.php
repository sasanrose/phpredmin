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
                $result = isset($_POST[$key]) ? filter_var($_POST[$key], FILTER_SANITIZE_STRING) : $default;
                break;
            case Router::PUT:
                parse_str(file_get_contents("php://input"), $vars);
                $result = isset($vars[$key]) ? filter_var($vars[$key], FILTER_SANITIZE_STRING) : $default;
                break;
            case Router::GET:
                $result = filter_var(Router::instance()->query($key, $default), FILTER_SANITIZE_STRING);
                break;
            default:
                $result = $default;
        }

        return $result;
    }

}
