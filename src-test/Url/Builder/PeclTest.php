<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Test\Url\Builder;

use PhpRedmin\Url\Builder\Pecl;
use PHPUnit\Framework\TestCase;

class PeclTest extends TestCase
{
    /**
     * @expectedException \PhpRedmin\Exception\Url
     * @expectedExceptionMessage Host is required
     */
    public function testNoHost()
    {
        $url = new Pecl();
        $url->toString();
    }

    public function testDefaultScheme()
    {
        $url = new Pecl();
        $url->setHost('alpha.com');

        $got = $url->toString();

        $this->assertEquals('http://alpha.com/', $got);
    }

    public function testAll()
    {
        $url = new Pecl();
        $url->setHost('alpha.com');
        $url->setScheme('https');
        $url->setPath('test/path');
        $url->setQuery(['alpha' => 'beta']);

        $got = $url->toString();

        $this->assertEquals('https://alpha.com/test/path?alpha=beta', $got);
    }
}
