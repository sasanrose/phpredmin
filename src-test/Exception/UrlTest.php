<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Test\Exception;

use PhpRedmin\Exception\Url;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    public function testGetUrlDetails()
    {
        try {
            throw new Url('Test message', 'Test details');
        } catch (\Exception $e) {
            $this->assertEquals('Test details', $e->getUrl());
        }
    }
}
