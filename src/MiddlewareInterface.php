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

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface MiddlewareInterface
{
    /**
     * Process an incoming request and/or response.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param callable          $next
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next);
}
