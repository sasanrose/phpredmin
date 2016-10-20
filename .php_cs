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

$header = <<<EOF
This file is part of the "PHP Redis Admin" package.

(c) Faktiva (http://faktiva.com)

NOTICE OF LICENSE
This source file is subject to the CC BY-SA 4.0 license that is
available at the URL https://creativecommons.org/licenses/by-sa/4.0/

DISCLAIMER
This code is provided as is without any warranty.
No promise of being safe or secure

@author   Sasan Rose <sasan.rose@gmail.com>
@author   Emiliano 'AlberT' Gabrielli <albert@faktiva.com>
@license  https://creativecommons.org/licenses/by-sa/4.0/  CC-BY-SA-4.0
@source   https://github.com/faktiva/php-redis-admin
EOF;

Symfony\CS\Fixer\Contrib\HeaderCommentFixer::setHeader($header);

return Symfony\CS\Config\Config::create()
    ->setUsingCache(true)
    ->level(Symfony\CS\FixerInterface::PSR2_LEVEL)
    ->fixers(array(
        'header_comment',
        'whitespacy_lines',
        'long_array_syntax',
        'single_quote',
        'unused_use',
        'pre_increment',
        '-psr0', /*XXX*/
    ))
    ->finder(
        Symfony\CS\Finder\DefaultFinder::create()
            ->exclude(array('vendor', 'var', 'bin'))
            ->in(__DIR__)
    )
;

// vim:ft=php
