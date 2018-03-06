<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Middleware;

use PhpRedmin\MiddlewareInterface;
use PhpRedmin\Traits;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Request implements MiddlewareInterface
{
    use Traits\Request;

    /**
     * {@inheritdoc}
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        $action = (array) $this->getValueFromRequest($request, 'action', '');
        $request->withAttribute('action', current($action));

        $keys = (array) $this->getValueFromRequest($request, 'keys', []);
        $request->withAttribute('keys', $keys);

        return $next($request, $response);
    }
}
