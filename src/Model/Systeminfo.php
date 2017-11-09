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

class Systeminfo
{
    use Traits\Redis;

    /**
     * Redis instance to connect to Redis.
     *
     * @var Redis
     */
    protected $redis;

    /**
     * Group model.
     *
     * @var Group
     */
    protected $group;

    /**
     * User model.
     *
     * @var User
     */
    protected $user;

    /**
     * User model constructor.
     *
     * @param User  $user
     * @param Group $group
     * @param Redis $redis
     */
    public function __construct(
        User $user,
        Group $group,
        Redis $redis
    ) {
        $this->user = $user;
        $this->group = $group;
        $this->redis = $redis;
    }

    /**
     * Check if the system is installed or not.
     *
     * @return bool
     */
    public function isInstalled(): bool
    {
        $systemInfoKey = $this->prepareKey('system', 'info');

        return $this->redis->exists($systemInfoKey);
    }

    /**
     * Installs the systeminfo.
     *
     * @param string $name
     * @param string $email
     * @param string $firstname
     * @param string $lastname
     * @param string $password
     *
     * @return bool
     */
    public function install(
        string $name,
        string $email,
        string $firstname,
        string $lastname,
        string $password
    ): bool {
        $systemInfoKey = $this->prepareKey('system', 'info');

        $this->startTransaction($this->redis, $systemInfoKey);

        $this->redis->hmset($systemInfoKey, [
            'name' => $name,
            'email' => $email,
        ]);

        $result = $this->commitTransaction($this->redis);

        if (FALSE === $result) {
            return FALSE;
        }

        $userKey = $this->user->create($email, $firstname, $lastname, $password);
        $groupKey = $this->group->create('administrators', _('Administrators'));

        if (FALSE === $userKey ||
            FALSE === $groupKey ||
            FALSE === $this->group->addUserToGroup('administrators', $email)) {
            return FALSE;
        }

        return TRUE;
    }
}
