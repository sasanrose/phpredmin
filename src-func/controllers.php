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

use PhpRedmin\Controller\Installer;
use PhpRedmin\Controller\InstallerInterface;
use PhpRedmin\Model\Systeminfo;
use PhpRedmin\Url\UrlBuilderInterface;
use PhpRedmin\Validator\FormValidatorInterface;
use Pimple\Container;
use Psr\Log\LoggerInterface;
use Twig\Environment;

function controllers(Container $c)
{
    $c[InstallerInterface::class] = function ($c) {
        return new Installer(
            $c[Environment::class],
            $c[UrlBuilderInterface::class],
            $c[FormValidatorInterface::class],
            $c[Systeminfo::class],
            $c[LoggerInterface::class]
        );
    };

    return $c;
}
