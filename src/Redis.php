<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin;

use Redis as PhpRedis;

class Redis extends PhpRedis
{
    /**
     * Selected db index.
     *
     * @var int
     */
    protected $dbIndex = 0;

    /**
     * Selected redis server index.
     *
     * @var int
     */
    protected $serverIndex = 0;

    /**
     * If we are inside a redis multi transaction or not.
     *
     * @var bool
     */
    protected $redisTransaction = FALSE;

    /**
     * Starts a transaction.
     */
    public function startTransaction(): void
    {
        $this->redisTransaction = TRUE;
    }

    /**
     * Commits a transaction.
     */
    public function commitTransaction(): void
    {
        $this->redisTransaction = FALSE;
    }

    /**
     * Returns wether a transaction started or not.
     *
     * @return bool
     */
    public function isTransactionStrated(): bool
    {
        return $this->redisTransaction;
    }

    /**
     * Sets a connected redis server index.
     *
     * @param int
     */
    public function setServerIndex(int $index): void
    {
        $this->serverIndex = $index;
    }

    /**
     * Returns the connected redis server index.
     *
     * @return int
     */
    public function getServerIndex(): int
    {
        return $this->serverIndex;
    }

    /**
     * Sets a selected redis db index.
     *
     * @param int
     */
    public function setDbIndex(int $index): void
    {
        $this->dbIndex = $index;
    }

    /**
     * Returns the connected redis server index.
     *
     * @return int
     */
    public function getDbIndex(): int
    {
        return $this->dbIndex;
    }
}
