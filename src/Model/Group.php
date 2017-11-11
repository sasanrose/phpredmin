<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Model;

use PhpRedmin\Traits;
use Redis;

class Group
{
    use Traits\Redis;

    /**
     * Redis instance to connect to Redis.
     *
     * @var Redis
     */
    protected $redis;

    /**
     * Group model constructor.
     *
     * @param Redis
     */
    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * Creates a group.
     *
     * @param string name
     * @param string desc
     *
     * @return mixed
     */
    public function create(
        string $name,
        string $desc
    ) {
        $groupKey = $this->prepareKey('group', $name);
        $groupsKey = $this->prepareKey('groups');

        $this->startTransaction($this->redis, $groupKey);

        $this->redis->hmset($groupKey, [
            'desc' => $desc,
        ]);

        $this->redis->sAdd($groupsKey, $groupKey);

        $result = $this->commitTransaction($this->redis);

        if (FALSE !== $result) {
            return $groupKey;
        }

        return FALSE;
    }

    /**
     * Returns if group exists or not.
     *
     * @param string $group
     *
     * @return bool
     */
    public function exists(string $group): bool
    {
        $groupKey = $this->prepareKey('group', $group);
        $groupsKey = $this->prepareKey('groups');

        return $this->redis->sismember($groupsKey, $groupKey);
    }

    /**
     * Adds a user to a group.
     *
     * @param string $group
     * @param string $email
     *
     * @return mixed
     */
    public function addUserToGroup(string $group, string $email)
    {
        $groupKey = $this->prepareKey('group', $group);
        $userKey = $this->prepareKey('user', $email);
        $groupMembersKey = $this->prepareKey(['group', 'members'], $group);
        $currentGroup = NULL;

        if ($this->redis->hexists($userKey, 'group')) {
            $currentGroup = $this->redis->hget($userKey, 'group');
        }

        $this->startTransaction($this->redis, $userKey);

        $this->redis->hset($userKey, 'group', $groupKey);

        $this->redis->sAdd($groupKey, $userKey);

        if (isset($currentGroup)) {
            $this->redis->sRem($currentGroup, $userKey);
        }

        return $this->commitTransaction($this->redis);
    }

    /**
     * Checks if a user is a member of a specific group or not.
     *
     * @param string $group
     * @param string $email
     *
     * @return bool
     */
    public function isMember(string $group, string $email): bool
    {
        $userKey = $this->prepareKey('user', $email);
        $groupKey = $this->prepareKey('group', $group);

        return $this->redis->sismember($groupKey, $userKey);
    }
}
