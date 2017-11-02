<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Test\Integration\Zend\Diactoros;

use PhpRedmin\Integration\Zend\Diactoros\Response;
use PHPUnit\Framework\TestCase;

/**
 * @group integration
 */
class ResponseTest extends TestCase
{
    public function testRedirect()
    {
        $response = new Response();
        $response = $response->withRedirect('test-uri', 302);

        $this->assertEquals(['location' => ['test-uri']], $response->getHeaders());
        $this->assertEquals(302, $response->getStatusCode());
    }
}
