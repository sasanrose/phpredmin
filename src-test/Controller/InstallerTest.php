<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Test\Controller;

use PhpRedmin\Controller\Installer;
use PhpRedmin\Model\Systeminfo as Systeminfo;
use PhpRedmin\Test\Phpunit\Traits;
use PhpRedmin\Traits\Redis as RedisTrait;
use PhpRedmin\Url\UrlBuilderInterface;
use PHPUnit\Framework\TestCase;

/**
 * @group controller
 */
class InstallerTest extends TestCase
{
    use RedisTrait;
    use Traits\Controller;

    protected $urlBuilder;
    protected $model;

    public function setUp()
    {
        $this->urlBuilder = $this->createMock(UrlBuilderInterface::class);
        $this->model = $this->createMock(Systeminfo::class);

        $this->createControllerMocks();
    }

    public function testAlreadyInstalled()
    {
        $this->installed('install');
    }

    public function testAlreadyInstalledDoInstall()
    {
        $this->installed('doInstall');
    }

    protected function installed($method)
    {
        $this->model
            ->expects($this->once())
            ->method('isInstalled')
            ->willReturn(TRUE);

        $this->urlBuilder
            ->expects($this->once())
            ->method('toString')
            ->willReturn('test-uri');

        $this->response
            ->expects($this->once())
            ->method('withRedirect')
            ->with('test-uri');

        $this->logger
            ->expects($this->once())
            ->method('debug');

        $installer = $this->getController();
        $installer->$method($this->request, $this->response);
    }

    public function testNotInstalled()
    {
        $this->mockResponse('controller/installer/form.twig');

        $this->model
            ->expects($this->once())
            ->method('isInstalled')
            ->willReturn(FALSE);

        $installer = $this->getController();
        $installer->install($this->request, $this->response);
    }

    public function testDoInstallValidationError()
    {
        $this->model
            ->expects($this->once())
            ->method('isInstalled')
            ->willReturn(FALSE);

        $values = ['password' => 'pass1', 'repassword' => 'pass2'];
        $errors = ['error1' => 'error'];

        $this->mockValidation(6, FALSE, $values, $errors);

        $errors['password'] = 'Passwords do not match';

        $this->mockResponse('controller/installer/form.twig', ['fields' => $values, 'errors' => $errors]);

        $installer = $this->getController();
        $installer->doInstall($this->request, $this->response);
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
        $values = [
            'name' => 'PHPRedmin',
            'firstname' => 'Alhpa',
            'lastname' => 'Bravo',
            'email' => 'alpha@phpredmin.com',
            'password' => 'AlphaBravo1234',
            'repassword' => 'AlphaBravo1234',
        ];

        $this->mockValidation(6, TRUE, $values, []);

        $this->model
            ->expects($this->once())
            ->method('install')
            ->with(
                $values['name'],
                $values['email'],
                $values['firstname'],
                $values['lastname'],
                $values['password']
            )
            ->willReturn($result);

        $templateName = $result ? 'controller/installer/success.twig'
                                : 'controller/installer/failed.twig';

        $this->mockResponse($templateName);

        $installer = $this->getController();
        $installer->doInstall($this->request, $this->response);
    }

    protected function getController()
    {
        return new Installer(
            $this->twig,
            $this->urlBuilder,
            $this->validator,
            $this->model,
            $this->logger
        );
    }
}
