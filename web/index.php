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

define('ROOT_DIR', dirname(__DIR__)); //XXX
require_once ROOT_DIR.'/vendor/autoload.php';

$config = App::instance()->config;

if (isset($config['timezone'])) {
    date_default_timezone_set($config['timezone']);
}

$authenticated = true;

if (PHP_SAPI !== 'cli' && isset(App::instance()->config['auth'])) {
    $username = null;
    $password = null;

    $auth = $config['auth'];

    if (isset($auth['username']) && isset($auth['password'])) {
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
    if (1 || isset($config['debug']) && $config['debug']) {
        Symfony\Component\Debug\Debug::enable();
    }

    Router::instance()->route();
} else {
    header('WWW-Authenticate: Basic realm="PHPRedis Administrator"');
    header('HTTP/1.0 401 Unauthorized');
    die('Not Authorized');
}
