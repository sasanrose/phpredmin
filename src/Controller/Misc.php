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
use Twig\Environment;

class Misc implements MiscInterface
{
    /**
     * Twig Environment.
     *
     * @var Twig\Environment
     */
    protected $twig;

    /**
     * Misc constructor.
     *
     * @param Twig\Environment
     */
    public function __construct(
        Environment $twig
    ) {
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function accessDenied(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write($this->twig->render('controller/misc/access-denied.twig'));

        return $response;
    }
}
