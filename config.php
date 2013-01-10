<?php
$config = Array(
    'default_controller' => 'Welcome',
    'default_action'     => 'Index',
    'production'         => True,
    'default_layout'     => 'layout',
    'timezone'           => 'Asia/Tehran',
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
