<?php
function __autoload($class)
{
    $path = '../';
    if (preg_match('/^(.*)_Controller$/', $class, $matches)) {
        $class = $matches[1];
        $dir   = 'controllers';
    } elseif (preg_match('/^(.*)_Model$/', $class, $matches)) {
        $class = $matches[1];
        $dir   = 'models';
    } elseif (preg_match('/^(.*)_Helper$/', $class, $matches)) {
        $class = $matches[1];
        $dir   = 'helpers';
    } else {
        $dir = 'libraries';
    }
    include_once($path.$dir.'/'.(strtolower($class)).'.php');
}
if (isset(App::instance()->config['timezone'])) {
    date_default_timezone_set(App::instance()->config['timezone']);

}

$authenticated = true;

if (PHP_SAPI !== 'cli' && isset(App::instance()->config['auth'])) {
    $username = null;
    $password = null;

    $auth = App::instance()->config['auth'];

if(isset($auth['username']) && isset($auth['password']))
    {
        // mod_php
        if (isset($_SERVER['PHP_AUTH_USER'])) {
            $username = $_SERVER['PHP_AUTH_USER'];
            $password = $_SERVER['PHP_AUTH_PW'];
            // most other servers
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION']) && strpos(strtolower($_SERVER['HTTP_AUTHORIZATION']), 'basic') === 0) {
      		list($username, $password) = explode(':', base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
        }

        if ($username != $auth['username'] || !password_verify($password, $auth['password'])) {
            $authenticated = false;
        }
    }
}

if ($authenticated) {
    $error = new Error();
    Router::instance()->route();
} else {
    header('WWW-Authenticate: Basic realm="PHPRedis Administrator"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Not Authorized';
    die();
}
