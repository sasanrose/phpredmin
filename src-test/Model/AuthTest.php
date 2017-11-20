<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Test\Model;

use PhpRedmin\Model\Auth;
use PhpRedmin\Redis;
use PhpRedmin\Test\Phpunit\Traits as PhpunitTraits;
use PhpRedmin\Traits;
use PHPUnit\Framework\TestCase;

/**
 * @group model
 */
class AuthTest extends TestCase
{
    use PhpunitTraits\Redis;
    use Traits\Redis;

    protected $model;

    public function setUp()
    {
        $this->redis = $this->createMock(Redis::class);

        $this->model = new Auth($this->redis);
    }

    public function testSuccessAuthenticate()
    {
        $got = $this->authenticate(password_hash('pass', PASSWORD_BCRYPT));

        $this->assertTrue($got);
    }

    public function testWrongPass()
    {
        $got = $this->authenticate(password_hash('correctpass', PASSWORD_BCRYPT));

        $this->assertFalse($got);
    }

    public function testWrongEmail()
    {
        $got = $this->authenticate(FALSE);

        $this->assertFalse($got);
    }

    protected function authenticate($return)
    {
        $userKey = $this->prepareKey('user', 'email');

        $this->mockStartWatchTransaction(TRUE, $userKey);

        $this->redis
            ->expects($this->once())
            ->method('hget')
            ->with($userKey, 'password');

        $this->mockCommitTransaction([$return]);

        return $this->model->authenticate('email', 'pass');
    }
}
