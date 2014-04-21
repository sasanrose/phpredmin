<?php
$config = Array(
    'default_controller' => 'Welcome',
    'default_action'     => 'Index',
    'production'         => True,
    'default_layout'     => 'layout',
    'timezone'           => 'Europe/Amsterdam',
    'log' => Array(
        'driver'    => 'file',
        'threshold' => 1, /* 0: Disable Logging 1: Error 2: Notice 3: Info 4: Warning 5: Debug */
        'file'      => Array(
            'directory' => 'logs'
        )
    ),
    'database'  => Array(
        'driver' => 'redis',
        'mysql'  => Array(
            'host'     => 'localhost',
            'username' => 'root',
            'password' => 'root'
        ),
        'redis' => Array(
            Array(
                'host'     => 'localhost',
                'port'     => '6379',
                'password' => Null,
                'database' => 0,
                'max_databases' => 16, /* Manual configuration of max databases for Redis < 2.6 */
                'stats'    => Array(
                    'enable'   => 1,
                    'database' => 0,
                ),
                'dbNames' => Array( /* Name databases. key should be database id and value is the name */
                ),
            ),
        ),
    ),
    'session' => Array(
        'lifetime'       => 7200,
        'gc_probability' => 2,
        'name'           => 'phpredminsession'
    ),
    'gearman' => Array(
        'host' => '127.0.0.1',
        'port' => 4730
    ),
    'terminal' => Array(
        'enable'  => True,
        'history' => 200
    )
);

return $config;
