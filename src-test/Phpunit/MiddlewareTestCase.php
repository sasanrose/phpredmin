<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Test\Phpunit;

use PhpRedmin\Integration\Zend\Diactoros\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class MiddlewareTestCase extends TestCase
{
    /**
     * Next callable function.
     *
     * @var callable
     */
    protected $next;

    /**
     * Request mock.
     *
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * Response mock.
     *
     * @var Response
     */
    protected $response;

    /**
     * @SuppressWarnings(unused)
     */
    public function setUp()
    {
        parent::setUp();

        // Next middleware will be called (Returning the same resp)
        $this->next = function ($req, $resp) {
            return $resp;
        };

        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(Response::class);
    }
}
