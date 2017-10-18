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
use PhpRedmin\Model\Systeminfo;
use PhpRedmin\Model\User;
use PhpRedmin\Test\Phpunit\Traits as PhpunitTraits;
use PhpRedmin\Traits;
use PHPUnit\Framework\TestCase;
use Redis;

/**
 * @group model
 */
class SysteminfoTest extends TestCase
{
    use PhpunitTraits\Redis;
    use Traits\Redis;

    protected $model;
    protected $userModel;
    protected $groupModel;

    public function setUp()
    {
        $this->redis = $this->createMock(Redis::class);

        $this->userModel = $this->createMock(User::class);
        $this->groupModel = $this->createMock(Group::class);

        $this->model = new Systeminfo(
            $this->userModel,
            $this->groupModel,
            $this->redis
        );
    }

    public function testIsInstalled()
    {
        $systemInfoKey = $this->prepareKey('system', 'info');

        $this->redis
            ->expects($this->once())
            ->method('exists')
            ->with($systemInfoKey);

        $this->model->isInstalled();
    }

    public function testFailedCommitInstall()
    {
        $systemDetails = [
            'name' => 'phpredmin',
            'firstname' => 'Alhpa',
            'lastname' => 'Bravo',
            'email' => 'alpha@phpredmin.com',
            'password' => 'AlphaBravo1234',
        ];

        $systemInfoKey = $this->prepareKey('system', 'info');

        $this->mockStartWatchTransaction(TRUE, $systemInfoKey);
        $this->mockCommitTransaction(FALSE);

        $this->redis
            ->expects($this->once())
            ->method('hmset');

        $this->model->install(
            $systemDetails['name'],
            $systemDetails['email'],
            $systemDetails['firstname'],
            $systemDetails['lastname'],
            $systemDetails['password']
        );
    }

    public function testInstallSuccess()
    {
        $this->install(TRUE);
    }

    public function testInstallFailed()
    {
        $this->install(FALSE);
    }

    protected function install($result)
    {
        $systemDetails = [
            'name' => 'phpredmin',
            'firstname' => 'Alhpa',
            'lastname' => 'Bravo',
            'email' => 'alpha@phpredmin.com',
            'password' => 'AlphaBravo1234',
        ];

        $systemInfoKey = $this->prepareKey('system', 'info');
        $userKey = $this->prepareKey('user', $systemDetails['email']);
        $groupKey = $this->prepareKey('group', 'admin');

        $this->mockStartWatchTransaction(TRUE, $systemInfoKey);
        $this->mockCommitTransaction(TRUE);

        $this->redis
            ->expects($this->once())
            ->method('hmset')
            ->with($systemInfoKey, $this->callback(function ($details) use ($systemDetails) {
                if (!isset($details['name']) ||
                    $details['name'] !== $systemDetails['name']) {
                    return FALSE;
                }

                if (!isset($details['email']) ||
                    $details['email'] !== $systemDetails['email']) {
                    return FALSE;
                }

                return TRUE;
            }));

        $this->userModel
            ->expects($this->once())
            ->method('create')
            ->with(
                $systemDetails['email'],
                $systemDetails['firstname'],
                $systemDetails['lastname'],
                $systemDetails['password']
            )
            ->willReturn($userKey);

        $this->groupModel
            ->expects($this->once())
            ->method('create')
            ->with(
                'administrators',
                $this->anything()
            )
            ->willReturn($groupKey);

        $this->groupModel
            ->expects($this->once())
            ->method('addUserToGroup')
            ->with('administrators', $systemDetails['email'])
            ->willReturn($result);

        $this->model->install(
            $systemDetails['name'],
            $systemDetails['email'],
            $systemDetails['firstname'],
            $systemDetails['lastname'],
            $systemDetails['password']
        );
    }
}
