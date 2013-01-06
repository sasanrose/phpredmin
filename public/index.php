<?php

function __autoload($class)
{
    $path = '..'.DIRECTORY_SEPARATOR;
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

    include_once($path.$dir.DIRECTORY_SEPARATOR.(strtolower($class)).'.php');
}

Router::instance()->route();
