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

use PhpRedmin\Model\User;
use PhpRedmin\Test\Phpunit\Traits as PhpunitTraits;
use PhpRedmin\Traits;
use PHPUnit\Framework\TestCase;
use Redis;

/**
 * @group model
 */
class UserTest extends TestCase
{
    use PhpunitTraits\Redis;
    use Traits\Redis;

    protected $model;

    public function setUp()
    {
        $this->redis = $this->createMock(Redis::class);

        $this->model = new User($this->redis);
    }

    public function testCreateSuccess()
    {
        $this->create(TRUE);
    }

    public function testCreateFailed()
    {
        $this->create(FALSE);
    }

    protected function create($result)
    {
        $userDetails = [
            'firstname' => 'Alhpa',
            'lastname' => 'Bravo',
            'email' => 'alpha@phpredmin.com',
            'password' => 'AlphaBravo1234',
        ];

        $userKey = $this->prepareKey('user', $userDetails['email']);
        $usersKey = $this->prepareKey('users');

        $this->mockStartWatchTransaction(TRUE, $userKey);

        $this->redis
            ->expects($this->once())
            ->method('hmset')
            ->with($userKey, $this->callback(function ($details) use ($userDetails) {
                foreach ($details as $key => $value) {
                    if ('password' !== $key) {
                        if ($value !== $userDetails[$key]) {
                            return FALSE;
                        }
                        continue;
                    }

                    if (60 != strlen($value)) {
                        return FALSE;
                    }
                }

                return TRUE;
            }));

        $this->redis
            ->expects($this->once())
            ->method('sadd')
            ->with($usersKey, $userKey);

        $this->mockCommitTransaction($result);

        $got = $this->model->create(
            $userDetails['email'],
            $userDetails['firstname'],
            $userDetails['lastname'],
            $userDetails['password']
        );

        if (!$result) {
            $this->assertFalse($got);

            return;
        }

        $this->assertEquals($userKey, $got);
    }

    public function testExists()
    {
        $userKey = $this->prepareKey('user', 'alpha');
        $usersKey = $this->prepareKey('users');

        $this->redis
            ->expects($this->once())
            ->method('sismember')
            ->with($usersKey, $userKey)
            ->willReturn(TRUE);

        $this->model->exists('alpha');
    }

    public function testGetUserData()
    {
        $userKey = $this->prepareKey('user', 'alpha');

        $this->redis
            ->expects($this->once())
            ->method('sismember')
            ->willReturn(TRUE);

        $this->redis
            ->expects($this->once())
            ->method('hgetall')
            ->with($userKey)
            ->willReturn(['userdata']);

        $this->model->get('alpha');
    }

    /**
     * @expectedException \Exception
     */
    public function testGetUserDataNotFound()
    {
        $this->redis
            ->expects($this->once())
            ->method('sismember')
            ->willReturn(FALSE);

        $this->model->get('alpha');
    }
}
