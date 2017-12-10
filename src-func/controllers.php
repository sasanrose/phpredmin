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

use PhpRedmin\Model\Auth;
use PhpRedmin\Model\Systeminfo;
use PhpRedmin\Model\User;
use PhpRedmin\Url\UrlBuilderInterface;
use PhpRedmin\Validator\FormValidatorInterface;
use Pimple\Container;
use Psr\Log\LoggerInterface;
use Twig\Environment;

function controllers(Container $c)
{
    $c[Controller\InstallerInterface::class] = function ($c) {
        return new Controller\Installer(
            $c[Environment::class],
            $c[UrlBuilderInterface::class],
            $c[FormValidatorInterface::class],
            $c[Systeminfo::class],
            $c[LoggerInterface::class]
        );
    };

    $c[Controller\LoginInterface::class] = function ($c) {
        return new Controller\Login(
            $c[Environment::class],
            $c[UrlBuilderInterface::class],
            $c[FormValidatorInterface::class],
            $c[Auth::class],
            $c[User::class],
            $c[LoggerInterface::class]
        );
    };

    $c[Controller\MiscInterface::class] = function ($c) {
        return new Controller\Misc(
            $c[Environment::class]
        );
    };

    return $c;
}
