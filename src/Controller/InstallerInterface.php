<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface InstallerInterface
{
    /**
     * Install method to show the installation page.
     *
     * @param ServerRequestInterface
     * @param ResponseInterface
     *
     * @return ResponseInterface
     */
    public function install(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface;

    /**
     * Process the installation form.
     *
     * @param ServerRequestInterface
     * @param ResponseInterface
     *
     * @return ResponseInterface
     */
    public function doInstall(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface;
}
