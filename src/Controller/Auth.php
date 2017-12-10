<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Controller;

use PhpRedmin\Model\Auth as AuthModel;
use PhpRedmin\Model\User;
use PhpRedmin\Url\UrlBuilderInterface;
use PhpRedmin\Validator\FormValidatorInterface as FormValidator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use PSR7Sessions\Storageless\Session\SessionInterface;
use Twig\Environment;

class Auth implements AuthInterface
{
    use LoggerAwareTrait;

    /**
     * Auth model.
     *
     * @var Systeminfo
     */
    protected $authModel;

    /**
     * Twig Environment.
     *
     * @var Twig\Environment
     */
    protected $twig;

    /**
     * Url builder.
     *
     * @var UrlBuilderInterface
     */
    protected $urlBuilder;

    /**
     * User model.
     *
     * @var Systeminfo
     */
    protected $userModel;

    /**
     * Form validator.
     *
     * @var FormValidator
     */
    protected $validator;

    /**
     * Login constructor.
     *
     * @param Twig\Environment
     * @param UrlBuilderInterface
     * @param FromValidator
     * @param AuthModel
     * @param User
     * @param LoggerInterface
     */
    public function __construct(
        Environment $twig,
        UrlBuilderInterface $urlBuilder,
        FormValidator $validator,
        AuthModel $authModel,
        User $userModel,
        LoggerInterface $logger
    ) {
        $this->twig = $twig;
        $this->urlBuilder = $urlBuilder;
        $this->validator = $validator;
        $this->authModel = $authModel;
        $this->userModel = $userModel;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function login(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        if ($this->isAlreadyLoggedIn($session)) {
            return $response->withRedirect($this->urlBuilder->toString());
        }

        $response->getBody()->write($this->twig->render('controller/login/form.twig'));

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function doLogin(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        if ($this->isAlreadyLoggedIn($session)) {
            return $response->withRedirect($this->urlBuilder->toString());
        }

        $this->validator->addField('email', _('Email'), FormValidator::REQUIRED, [
            FILTER_SANITIZE_EMAIL,
            FILTER_VALIDATE_EMAIL,
        ]);

        $this->validator->addField('password', _('Password'), FormValidator::REQUIRED);

        $result = $this->validator->validate($request);
        $fields = $this->validator->getValues();
        $errors = $this->validator->getErrors();

        if ($result &&
            FALSE === $this->authModel->authenticate($fields['email'], $fields['password'])) {
            $result = FALSE;
            $errors['email'] = _('Invalid email or password');
        }

        if (!$result) {
            $response->getBody()->write($this->twig->render('controller/login/form.twig', [
                'errors' => $errors,
            ]));

            return $response;
        }

        $session->set('email', $fields['email']);
        $session->set('user', $this->userModel->get($fields['email']));

        $path = $session->get('redirect-path', NULL);

        if (isset($path)) {
            $this->urlBuilder->setPath($path);
        }

        return $response->withRedirect($this->urlBuilder->toString());
    }

    /**
     * {@inheritdoc}
     */
    public function doLogout(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        $session->clear();

        $this->urlBuilder->setPath('/');

        return $response->withRedirect($this->urlBuilder->toString());
    }

    /**
     * Is alredy logged in.
     *
     * @param SessionInterface $session
     *
     * @return bool
     */
    protected function isAlreadyLoggedIn(SessionInterface $session): bool
    {
        if ($session->has('email')) {
            $this->logger->debug('Already logged in. Redirecting to the main page');

            return TRUE;
        }

        return FALSE;
    }
}
