<?php
$config = Array(
    'default_controller' => 'Welcome',
    'default_action'     => 'Index',
    'production'         => True,
    'default_layout'     => 'layout',
    'timezone'           => 'Asia/Tehran',
    'log' => Array(
        'driver'    => 'file',
        'threshold' => 3, /* 0: Disable Logging 1: Error 2: Notice 3: Info 4: Warning 5: Debug */
        'file'      => Array(
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
