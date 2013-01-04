<?php
$config = Array(
    'default_controller' => 'Welcome',
    'default_action'     => 'Index',
    'default_layout'     => 'layout',
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
