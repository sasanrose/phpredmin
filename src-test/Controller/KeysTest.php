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
class KeysTest extends KeysTestCase
{
    public function testEmptyKey()
    {
        $errors['keys'] = 'Key is required';

        $this->errorTest(['action' => 'type', 'keys' => []], $errors);
    }

    public function testEmptyAction()
    {
        $errors['action'] = 'Action is required';

        $this->errorTest(['action' => '', 'keys' => ['key']], $errors);
    }

    public function testInvalidAction()
    {
        $errors['action'] = 'Invalid action';

        $this->errorTest(['action' => 'test', 'keys' => ['key']], $errors);
    }

    protected function errorTest($returnValue, $errors)
    {
        $this->request
            ->expects($this->once())
            ->method('getAttributes')
            ->willReturn($returnValue);

        $this->mockResponse('controller/keys/keys.twig', ['errors' => $errors]);

        $keys = $this->getController();
        $keys->search($this->request, $this->response);
    }
}
