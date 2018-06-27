<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Test\Controller;

use PhpRedmin\Controller\Keys as KeysController;
use PhpRedmin\Redis;
use PhpRedmin\Test\Phpunit\ControllerTestCase;
use PhpRedmin\Url\UrlBuilderInterface;

/**
 * @group controller
 */
class BlockingKeysTest extends KeysTestCase
{
    public function testKeyNotFound()
    {
        $this->request
            ->expects($this->once())
            ->method('getAttributes')
            ->willReturn(['action' => 'keys', 'keys' => ['key']]);

        $this->redis
            ->expects($this->once())
            ->method('keys')
            ->with('key')
            ->willReturn([]);

        $this->mockResponse('controller/keys/keys.twig', [
            'search' => 'key',
            'notFound' => TRUE,
        ]);

        $keys = $this->getController();
        $keys->search($this->request, $this->response);
    }
}
