<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Integration\Zend\Diactoros;

use Zend\Diactoros\Response as ZendResponse;

/**
 * Extends zend response to add redirect feature in a testable way that can be
 * injected via dependency injection.
 */
class Response extends ZendResponse
{
    /**
     * Creates a response with a redirect header.
     *
     * @param string $uri
     * @param int    $status
     *
     * @return ZendResponse
     */
    public function withRedirect(string $uri, int $status = 302): ZendResponse
    {
        $new = clone $this;
        $new = $new->withStatus($status);
        $new->headers = [];
        $new->headers['location'] = [$uri];

        return $new;
    }
}
