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

class Auth
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
     * Authenticates an email and password.
     *
     * @param string $email
     * @param string $password
     *
     * @return bool
     */
    public function authenticate(
        string $email,
        string $password
    ): bool {
        $userKey = $this->prepareKey('user', $email);

        $this->startTransaction($this->redis, $userKey);

        $this->redis->hget($userKey, 'password');

        $result = $this->commitTransaction($this->redis);

        if ($result && isset($result[0]) && FALSE !== $result[0]) {
            return password_verify($password, $result[0]);
        }

        return FALSE;
    }
}
