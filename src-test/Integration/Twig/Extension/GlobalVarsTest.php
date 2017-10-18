<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Test\Integration\Twig\Extension;

use PhpRedmin\Integration\Twig\Extension\GlobalVars;
use PHPUnit\Framework\TestCase;

/**
 * @group integration
 */
class GlobalVarsTest extends TestCase
{
    public function testGlobalVars()
    {
        $globals = new GlobalVars(['alpha']);

        $this->assertEquals(['alpha'], $globals->getGlobals());
    }
}
