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

use PhpRedmin\Controller\Auth;
use PhpRedmin\Model\Auth as AuthModel;
use PhpRedmin\Model\User;
use PhpRedmin\Test\Phpunit\ControllerTestCase;
use PhpRedmin\Url\UrlBuilderInterface;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use PSR7Sessions\Storageless\Session\SessionInterface;

/**
 * @group controller
 */
class AuthTest extends ControllerTestCase
{
    protected $urlBuilder;
    protected $authModel;
    protected $session;
    protected $userModel;

    public function setUp()
    {
        parent::setUp();

        $this->urlBuilder = $this->createMock(UrlBuilderInterface::class);
        $this->authModel = $this->createMock(AuthModel::class);
        $this->userModel = $this->createMock(User::class);
        $this->session = $this->createMock(SessionInterface::class);

        $this->request
            ->expects($this->once())
            ->method('getAttribute')
            ->with(SessionMiddleware::SESSION_ATTRIBUTE)
            ->willReturn($this->session);
    }

    public function testLoginAlreadyLoggedIn()
    {
        $this->alreadyLoggedIn('login');
    }

    public function testDoLoginAlreadyLoggedIn()
    {
        $this->alreadyLoggedIn('doLogin');
    }

    protected function alreadyLoggedIn($method)
    {
        $this->session
            ->expects($this->once())
            ->method('has')
            ->with('email')
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

    public function testNotLoggedIn()
    {
        $this->mockResponse('controller/login/form.twig');

        $this->session
            ->expects($this->once())
            ->method('has')
            ->with('email')
            ->willReturn(FALSE);

        $login = $this->getController();
        $login->login($this->request, $this->response);
    }

    public function testDoLogin()
    {
        $this->login(TRUE);

        $this->session
            ->expects($this->once())
            ->method('get')
            ->with('redirect-path')
            ->willReturn('testpath');

        $this->urlBuilder
            ->expects($this->once())
            ->method('setPath')
            ->with('testpath');

        $this->urlBuilder
            ->expects($this->once())
            ->method('toString')
            ->willReturn('test-uri');

        $this->response
            ->expects($this->once())
            ->method('withRedirect')
            ->with('test-uri');

        $login = $this->getController();
        $login->doLogin($this->request, $this->response);
    }

    public function testDoLoginFailed()
    {
        $this->login(FALSE);

        $errors = ['email' => 'Invalid email or password'];

        $this->mockResponse('controller/login/form.twig', ['errors' => $errors]);

        $login = $this->getController();
        $login->doLogin($this->request, $this->response);
    }

    public function testDoLoginValidationError()
    {
        $this->session
            ->expects($this->once())
            ->method('has')
            ->with('email')
            ->willReturn(FALSE);

        $values = ['email' => 'email', 'password' => 'pass2'];
        $errors = ['email' => 'Invalid email'];

        $this->mockValidation(2, FALSE, $values, $errors);

        $this->mockResponse('controller/login/form.twig', ['errors' => $errors]);

        $installer = $this->getController();
        $installer->doLogin($this->request, $this->response);
    }

    protected function login($result)
    {
        $values = [
            'email' => 'alpha@bravo.com',
            'password' => 'pass',
        ];

        $this->session
            ->expects($this->once())
            ->method('has')
            ->with('email')
            ->willReturn(FALSE);

        $this->mockValidation(2, TRUE, $values, []);

        $this->authModel
            ->expects($this->once())
            ->method('authenticate')
            ->with(
                $values['email'],
                $values['password']
            )
            ->willReturn($result);

        if ($result) {
            $this->session
                ->expects($this->exactly(2))
                ->method('set')
                ->withConsecutive(
                    ['email', $values['email']],
                    ['user', ['userDetails']]
                );

            $this->userModel
                ->expects($this->once())
                ->method('get')
                ->with('alpha@bravo.com')
                ->willReturn(['userDetails']);

            return;
        }

        $this->session
            ->expects($this->never())
            ->method('set');
    }

    protected function getController()
    {
        return new Auth(
            $this->twig,
            $this->urlBuilder,
            $this->validator,
            $this->authModel,
            $this->userModel,
            $this->logger
        );
    }
}
