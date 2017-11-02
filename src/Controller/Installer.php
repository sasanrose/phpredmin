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

use PhpRedmin\Model\Systeminfo;
use PhpRedmin\Url\UrlBuilderInterface;
use PhpRedmin\Validator\FormValidatorInterface as FormValidator;
use PhpRedmin\Validator\Traits\Password as PasswordValidator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Twig\Environment;

class Installer implements InstallerInterface
{
    use LoggerAwareTrait;
    use PasswordValidator;

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
     * Installer constructor.
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
        Systeminfo $model,
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
    public function install(ServerRequestInterface $request, ResponseInterface $response)
    {
        if ($this->checkSystemInstallation()) {
            $url = $this->urlBuilder->toString();

            return $response->withRedirect($url);
        }

        $response->getBody()->write($this->twig->render('controller/installer/form.twig'));

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function doInstall(ServerRequestInterface $request, ResponseInterface $response)
    {
        if ($this->checkSystemInstallation()) {
            $url = $this->urlBuilder->toString();

            return $response->withRedirect($url);
        }

        $this->validator->addField('name', _('Name'), FormValidator::REQUIRED, [FILTER_SANITIZE_STRING]);
        $this->validator->addField('firstname', _('Firstname'), FormValidator::REQUIRED, [FILTER_SANITIZE_STRING]);
        $this->validator->addField('lastname', _('Lastname'), FormValidator::REQUIRED, [FILTER_SANITIZE_STRING]);
        $this->validator->addField('email', _('Email'), FormValidator::REQUIRED, [
            FILTER_SANITIZE_EMAIL,
            FILTER_VALIDATE_EMAIL,
        ]);

        $this->validator->addField('password', _('Password'), FormValidator::REQUIRED, [FILTER_CALLBACK], [
            'options' => [$this, 'validatePassword'],
            'errorMsg' => [$this, 'getPasswordValidationErrorMsg'],
        ]);

        $this->validator->addField('repassword', _('Password repeat'), FormValidator::REQUIRED);

        $result = $this->validator->validate($request);
        $fields = $this->validator->getValues();
        $errors = $this->validator->getErrors();

        if (
            isset($fields['password']) &&
            isset($fields['repassword']) &&
            $fields['password'] !== $fields['repassword']
        ) {
            $result = FALSE;
            $errors['password'] = _('Passwords do not match');
        }

        if (!$result) {
            $response->getBody()->write($this->twig->render('controller/installer/form.twig', [
                'fields' => $fields,
                'errors' => $errors,
            ]));

            return $response;
        }

        $result = $this->model->install(
            $fields['name'],
            $fields['email'],
            $fields['firstname'],
            $fields['lastname'],
            $fields['password']
        );

        if (FALSE === $result) {
            $response->getBody()->write($this->twig->render('controller/installer/failed.twig'));

            return $response;
        }

        $response->getBody()->write($this->twig->render('controller/installer/success.twig', [
            'firstname' => $fields['firstname'],
            'lastname' => $fields['lastname'],
        ]));

        return $response;
    }

    /**
     * Check if system is installed, and if installed, it will redirect.
     *
     * @return bool
     */
    protected function checkSystemInstallation()
    {
        if ($this->model->isInstalled()) {
            $this->logger->debug('PhpRedmins is already installed.');

            return TRUE;
        }

        return FALSE;
    }
}
