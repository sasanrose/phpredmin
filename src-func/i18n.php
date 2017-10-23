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

function i18n(Container $c)
{
    if ($c['LOCALE']) {
        \putenv('LANGUAGE='.$c['LOCALE']);
        \setlocale(LC_ALL, $c['LOCALE']);

        $domain = 'messages'; // which language file to use
        \bindtextdomain($domain, __DIR__.'/../locale');
        \bind_textdomain_codeset($domain, 'UTF-8');

        \textdomain($domain);
    }

    return $c;
}
