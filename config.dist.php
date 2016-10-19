<?php

/*
 * This file is part of the "PHP Redis Admin" package.
 *
 * (c) Faktiva (http://faktiva.com)
 *
 * NOTICE OF LICENSE
 * This source file is subject to the CC BY-SA 4.0 license that is
 * available at the URL https://creativecommons.org/licenses/by-sa/4.0/
 *
 * DISCLAIMER
 * This code is provided as is without any warranty.
 * No promise of being safe or secure
 *
 * @author   Sasan Rose <sasan.rose@gmail.com>
 * @author   Emiliano 'AlberT' Gabrielli <albert@faktiva.com>
 * @license  https://creativecommons.org/licenses/by-sa/4.0/  CC-BY-SA-4.0
 * @source   https://github.com/faktiva/php-redis-admin
 */

$config = array(
    'default_controller' => 'Welcome',
    'default_action'     => 'Index',
    'production'         => true,
    'default_layout'     => 'layout',
    'timezone'           => 'Europe/Amsterdam',
    'auth' => array(
        'username' => 'admin',
        'password' => password_hash('admin', PASSWORD_DEFAULT)
    ),
    'log' => array(
        'driver'    => 'file',
        'threshold' => 1, /* 0: Disable Logging 1: Error 2: Notice 3: Info 4: Warning 5: Debug */
        'file'      => array(
            'directory' => 'logs'
        )
    ),
    'database'  => array(
        'driver' => 'redis',
        'mysql'  => array(
            'host'     => 'localhost',
            'username' => 'root',
            'password' => 'root'
        ),
        'redis' => array(
            array(
                'host'     => 'localhost',
                'port'     => '6379',
                'password' => null,
                'database' => 0,
                'max_databases' => 16, /* Manual configuration of max databases for Redis < 2.6 */
                'stats'    => array(
                    'enable'   => 1,
                    'database' => 0,
                ),
                'dbNames' => array( /* Name databases. key should be database id and value is the name */
                ),
            ),
        ),
    ),
    'session' => array(
        'lifetime'       => 7200,
        'gc_probability' => 2,
        'name'           => 'phpredminsession'
    ),
    'gearman' => array(
        'host' => '127.0.0.1',
        'port' => 4730
    ),
    'terminal' => array(
        'enable'  => true,
        'history' => 200
    )
);

return $config;
