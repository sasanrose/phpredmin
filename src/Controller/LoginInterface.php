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

interface LoginInterface
{
    /**
     * Login method to show the login page.
     *
     * @param ServerRequestInterface
     * @param ResponseInterface
     */
    public function login(ServerRequestInterface $request, ResponseInterface $response);

    /**
     * Process the login form.
     *
     * @param ServerRequestInterface
     * @param ResponseInterface
     */
    public function doLogin(ServerRequestInterface $request, ResponseInterface $response);
}
