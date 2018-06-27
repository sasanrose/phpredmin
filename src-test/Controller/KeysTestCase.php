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

class KeysTestCase extends ControllerTestCase
{
    protected $redis;
    protected $urlBuilder;

    public function setUp()
    {
        parent::setUp();

        $this->redis = $this->createMock(Redis::class);
        $this->urlBuilder = $this->createMock(UrlBuilderInterface::class);
    }

    protected function getController()
    {
        return new KeysController(
            $this->twig,
            $this->redis,
            $this->urlBuilder,
            $this->logger
        );
    }
}
