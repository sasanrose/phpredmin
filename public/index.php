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

$error = new Error();

Router::instance()->route();
