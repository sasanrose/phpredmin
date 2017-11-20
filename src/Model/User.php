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

use PhpRedmin\Redis;
use PhpRedmin\Traits;

class User
{
    use Traits\Redis;

    /**
     * Redis instance to connect to Redis.
     *
     * @var Redis
     */
    protected $redis;

    /**
     * User model constructor.
     *
     * @param Redis
     */
    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * Creates a user.
     *
     * @param string $email
     * @param string $firstname
     * @param string $lastname
     * @param string $password
     *
     * @return mixed
     */
    public function create(
        string $email,
        string $firstname,
        string $lastname,
        string $password
    ) {
        $password = password_hash($password, PASSWORD_BCRYPT);

        $userKey = $this->prepareKey('user', $email);
        $usersKey = $this->prepareKey('users');

        $this->startTransaction($this->redis, $userKey);

        $this->redis->hmset($userKey, [
            'email' => $email,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'password' => $password,
        ]);

        $this->redis->sAdd($usersKey, $userKey);

        $result = $this->commitTransaction($this->redis);

        if (FALSE !== $result) {
            return $userKey;
        }

        return FALSE;
    }

    /**
     * Returns if user exists or not.
     *
     * @param string $email
     *
     * @return bool
     */
    public function exists(string $email): bool
    {
        $userKey = $this->prepareKey('user', $email);
        $usersKey = $this->prepareKey('users');

        return $this->redis->sismember($usersKey, $userKey);
    }

    /**
     * Returns user data.
     *
     * @param string $email
     *
     * @throws \Exception
     *
     * @return array
     */
    public function get(string $email): ?array
    {
        if (!$this->exists($email)) {
            throw new \Exception("User with {$email} does not exist");
        }

        $userKey = $this->prepareKey('user', $email);

        return $this->redis->hgetall($userKey);
    }
}
