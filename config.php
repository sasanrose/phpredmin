<?php
$config = Array(
    'default_controller' => 'Welcome',
    'default_action'     => 'Index',
    'default_layout'     => 'layout',
    'log' => Array(
        'driver' => 'file',
        'file'   => Array(
            'directory' => 'logs'
        )
    ),
    'database'  => Array(
        'driver' => 'mysql',
        'mysql'  => Array(
            'host'     => 'localhost',
            'username' => 'root',
            'password' => 'root'
        )
    )
);

return $config;
