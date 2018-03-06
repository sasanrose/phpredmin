<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Traits;

use Psr\Http\Message\ServerRequestInterface;

trait Request
{
    /**
     * Extracts a value from query params or post body of a server request
     * based on a provided key.
     *
     * @var ServerRequestInterface $request
     * @var string                 $key
     * @var mixed                  $default
     *
     * @return mixed
     */
    protected function getValueFromRequest(ServerRequestInterface $request, $key, $default = NULL)
    {
        $queryParams = $request->getQueryParams();

        if (isset($queryParams[$key])) {
            return $queryParams[$key];
        }

        $parsedBody = $request->getParsedBody();

        if (isset($parsedBody[$key])) {
            return $parsedBody[$key];
        }

        return $default;
    }
}
