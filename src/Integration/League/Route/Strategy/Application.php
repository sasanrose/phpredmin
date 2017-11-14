<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Integration\League\Route\Strategy;

use League\Route\Http\Exception\MethodNotAllowedException;
use League\Route\Http\Exception\NotFoundException;
use League\Route\Strategy\ApplicationStrategy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Twig\Environment;

/**
 * This overrides default application decorators.
 */
class Application extends ApplicationStrategy
{
    /**
     * Logger.
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Twig mock.
     *
     * @var Twig\Environment
     */
    protected $twig;

    /**
     * Application strategy constructor.
     *
     * @param Twig\Environment
     * @param LoggerInterface
     */
    public function __construct(
        Environment $twig,
        LoggerInterface $logger
    ) {
        $this->twig = $twig;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function getNotFoundDecorator(NotFoundException $exception)
    {
        return [$this, 'notFound'];
    }

    /**
     * {@inheritdoc}
     */
    public function getMethodNotAllowedDecorator(MethodNotAllowedException $exception)
    {
        return [$this, 'notFound'];
    }

    /**
     * {@inheritdoc}
     */
    public function getExceptionDecorator(\Exception $exception)
    {
        $this->logger->error($exception->getMessage());

        return [$this, 'exceptionError'];
    }

    /**
     * Renders a not found page.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     *
     * @return ResponseInterface
     */
    public function notFound(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write($this->twig->render('controller/misc/not-found.twig'));

        return $response;
    }

    /**
     * Renders an error page.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     *
     * @return ResponseInterface
     */
    public function exceptionError(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write($this->twig->render('controller/misc/error.twig'));

        return $response;
    }
}
