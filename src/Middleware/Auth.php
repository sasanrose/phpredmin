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
use PhpRedmin\Url\UrlBuilderInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use PSR7Sessions\Storageless\Http\SessionMiddleware;

class Auth implements MiddlewareInterface
{
    /**
     * Url builder.
     *
     * @var UrlBuilderInterface
     */
    protected $urlBuilder;

    /**
     * Instantiates Install middleware.
     *
     * @param UrlBuilderInterface $urlBuilder
     */
    public function __construct(
        UrlBuilderInterface $urlBuilder
    ) {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        if (!$session->has('email')) {
            $uri = $request->getUri();
            $path = $uri->getPath();

            if (!preg_match('/^\/login/', $path)) {
                $this->urlBuilder->setPath('login');

                return $response->withRedirect($this->urlBuilder->toString());
            }
        }

        return $next($request, $response);
    }
}
