<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin;

use Pimple\Container;
use Zend\Log\Logger;

function envs(Container $c) {

    $servers = [];

    $i = 0;

    while (TRUE) {
        $addr = getenv('PHPREDMIN_REDIS_SERVERS_ADDR_' . $i);
        $port = getenv('PHPREDMIN_REDIS_SERVERS_PORT_' . $i);

        if ($addr === FALSE || $port === FALSE) {
            break;
        }

        $servers[$i] = ['ADDR' => $addr, 'PORT' => $port];
        $i++;
    }

    $c['REDIS_SERVERS'] = $servers;

    $c['JQUERY_VERSION'] = getenv('PHPREDMIN_JQUERY_VER') ?: '3.2.1';

    $c['TEMPLATES_DIR'] = getenv('PHPREDMIN_TEMPLATES_DIR') ?: __DIR__ . '/../templates';
    $c['TEMPLATES_CACHE_DIR'] = getenv('PHPREDMIN_TEMPLATES_CACHE_DIR') ?: __DIR__ . '/../templates-cache';

    $c['DEVELOPMENT_MODE'] = getenv('PHPREDMIN_DEVELOPMENT_MODE') ?: FALSE;

    $c['LOCALE'] = getenv('PHPREDMIN_LOCALE') ?: NULL;
    $c['UI_LANG'] = getenv('PHPREDMIN_UI_LANG') ?: NULL;
    $c['UI_LANG_DIR'] = getenv('PHPREDMIN_UI_LANG_DIR') ?: NULL;

    $c['LOG_LEVEL'] = getenv('PHPREDMIN_LOG_LEVEL') ?: Logger::INFO;

    return $c;
}
