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

use PhpRedmin\Controller\Misc;
use PhpRedmin\Test\Phpunit\ControllerTestCase;

/**
 * @group controller
 */
class MiscTest extends ControllerTestCase
{
    public function testAccessDenied()
    {
        $this->mockResponse('controller/misc/access-denied.twig');

        $misc = new Misc($this->twig);
        $misc->accessDenied($this->request, $this->response);
    }
}
