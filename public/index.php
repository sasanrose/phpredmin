<?php

function __autoload($class)
{
    $path = '../';
    if (preg_match('/^(.*)_Controller$/', $class, $matches)) {
        $class = $matches[1];
        $dir   = 'controllers';
    } elseif (preg_match('/^(.*)_Models$/', $class, $matches)) {
        $class = $matches[2];
        $dir   = 'models';
    } elseif (preg_match('/^(.*)_Helpers$/', $class, $matches)) {
        $class = $matches[2];
        $dir   = 'helpers';
    } else
        $dir = 'libraries';

    include_once($path.$dir.'/'.(strtolower($class)).'.php');
}

Router::instance()->route();
