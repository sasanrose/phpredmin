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

use PhpRedmin\Model\Auth;
use PhpRedmin\Url\UrlBuilderInterface;
use PhpRedmin\Validator\FormValidatorInterface as FormValidator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use PSR7Sessions\Storageless\Session\SessionInterface;
use Twig\Environment;

class Login implements LoginInterface
{
    use LoggerAwareTrait;

    /**
     * Systeminfo model.
     *
     * @var Systeminfo
     */
    protected $model;

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
     * @param Systeminfo
     * @param LoggerInterface
     */
    public function __construct(
        Environment $twig,
        UrlBuilderInterface $urlBuilder,
        FormValidator $validator,
        Auth $model,
        LoggerInterface $logger
    ) {
        $this->twig = $twig;
        $this->urlBuilder = $urlBuilder;
        $this->validator = $validator;
        $this->model = $model;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function login(ServerRequestInterface $request, ResponseInterface $response)
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        if ($this->isAlreadyLoggedIn($session)) {
            return;
        }

        $response->getBody()->write($this->twig->render('controller/login/form.twig'));

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function doLogin(ServerRequestInterface $request, ResponseInterface $response)
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        if ($this->isAlreadyLoggedIn($session)) {
            return;
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
            FALSE === $this->model->authenticate($fields['email'], $fields['password'])) {
            $result = FALSE;
            $errors['email'] = _('Invalid email or password');
        }

        if (!$result) {
            $response->getBody()->write($this->twig->render('controller/login/form.twig', [
                'errors' => $errors,
            ]));

            return $response;
        }

        $path = $session->get('redirect-path', NULL);

        if (isset($path)) {
            $this->urlBuilder->setPath($path);
        }

        $this->urlBuilder->redirect();

        return $response;
    }

    /**
     * Is alredy logged in.
     *
     * @param SessionInterface $session
     *
     * @return bool
     */
    protected function isAlreadyLoggedIn(SessionInterface $session)
    {
        if ($session->has('email')) {
            $url = $this->urlBuilder->toString();
            $this->logger->debug('Already logged in. Redirecting to the main page');
            $this->urlBuilder->redirect();

            return TRUE;
        }

        return FALSE;
    }
}
