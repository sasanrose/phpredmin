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

interface MiscInterface
{
    /**
     * Access denied page.
     *
     * @param ServerRequestInterface
     * @param ResponseInterface
     *
     * @return ResponseInterface
     */
    public function accessDenied(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface;

    /**
     * Main page.
     *
     * @param ServerRequestInterface
     * @param ResponseInterface
     *
     * @return ResponseInterface
     */
    public function main(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface;
}
