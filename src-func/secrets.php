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

function secrets(Container $c)
{
    foreach (scandir('/secrets/') as $secret) {
        if ('phpredmin_session_key' === $secret) {
            $c['SESSION_KEY'] = file_get_contents('/secrets/phpredmin_session_key');
        }

        if (preg_match('/^phpredmin_redis_server_pass_(\d+)$/', $secret, $matches)) {
            $serverIndex = $matches[1];

            if (!isset($c['REDIS_SERVERS'][$serverIndex])) {
                continue;
            }

            $c['REDIS_SERVERS'][$serverIndex] = file_get_contents("/secrets/{$secret}");
        }
    }

    return $c;
}
