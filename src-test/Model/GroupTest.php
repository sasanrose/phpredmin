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

use PhpRedmin\Model\Group;
use PhpRedmin\Test\Phpunit\Traits as PhpunitTraits;
use PhpRedmin\Traits;
use PHPUnit\Framework\TestCase;
use Redis;

/**
 * @group model
 */
class GroupTest extends TestCase
{
    use PhpunitTraits\Redis;
    use Traits\Redis;

    protected $model;

    public function setUp()
    {
        $this->redis = $this->createMock(Redis::class);

        $this->model = new Group($this->redis);
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
        $name = 'alpha';

        $groupDetails = [
            'desc' => 'alpha group',
        ];

        $groupKey = $this->prepareKey('group', $name);
        $groupsKey = $this->prepareKey('groups');

        $this->mockStartWatchTransaction(TRUE, $groupKey);

        $this->redis
            ->expects($this->once())
            ->method('hmset')
            ->with($groupKey, $this->callback(function ($details) use ($groupDetails) {
                foreach ($details as $key => $value) {
                    if ($value !== $groupDetails[$key]) {
                        return FALSE;
                    }
                }

                return TRUE;
            }));

        $this->redis
            ->expects($this->once())
            ->method('sadd')
            ->with($groupsKey, $groupKey);

        $this->mockCommitTransaction($result);

        $got = $this->model->create(
            $name,
            $groupDetails['desc']
        );

        if (!$result) {
            $this->assertFalse($got);

            return;
        }

        $this->assertEquals($groupKey, $got);
    }

    public function testExists()
    {
        $groupKey = $this->prepareKey('group', 'alpha');
        $groupsKey = $this->prepareKey('groups');

        $this->redis
            ->expects($this->once())
            ->method('sismember')
            ->with($groupsKey, $groupKey)
            ->willReturn(TRUE);

        $this->model->exists($groupKey);
    }

    public function testAddUser()
    {
        $groupName = 'alpha';
        $userEmail = 'alpha@bravo.com';

        $groupKey = $this->prepareKey('group', $groupName);
        $userKey = $this->prepareKey('user', $userEmail);
        $groupMembersKey = $this->prepareKey(['group', 'members'], $groupName);
        $currentGroup = 'alpha-old';

        $this->redis
            ->expects($this->once())
            ->method('hexists')
            ->with($userKey, 'group')
            ->willReturn(TRUE);

        $this->redis
            ->expects($this->once())
            ->method('hget')
            ->with($userKey, 'group')
            ->willReturn($currentGroup);

        $this->mockStartWatchTransaction(TRUE, $userKey);

        $this->redis
            ->expects($this->once())
            ->method('hset')
            ->with($userKey, 'group', $groupKey);

        $this->redis
            ->expects($this->once())
            ->method('sadd')
            ->with($groupKey, $userKey);

        $this->redis
            ->expects($this->once())
            ->method('srem')
            ->with($currentGroup, $userKey);

        $this->mockCommitTransaction(TRUE);

        $this->model->addUserToGroup(
            $groupName,
            $userEmail
        );
    }
}
