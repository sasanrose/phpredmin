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

use PhpRedmin\Redis as PhpRedminRedis;
use PhpRedmin\Url\UrlBuilderInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Twig\Environment;

class Keys implements KeysInterface
{
    use LoggerAwareTrait;
    use Traits\Keys\Type;

    /**
     * Redis instance to connect to Redis.
     *
     * @var PhpRedmin\Redis
     */
    protected $redis;

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
     * Installer constructor.
     *
     * @param Twig\Environment
     * @param PhpRedminRedis
     * @param UrlBuilderInterface
     * @param LoggerInterface
     */
    public function __construct(
        Environment $twig,
        PhpRedminRedis $redis,
        UrlBuilderInterface $urlBuilder,
        LoggerInterface $logger
    ) {
        $this->twig = $twig;
        $this->redis = $redis;
        $this->urlBuilder = $urlBuilder;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function search(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $attrs = $request->getAttributes();

        if (!isset($attrs['keys']) || empty($attrs['keys'])) {
            $errors = [
                'key' => _('Key is required'),
            ];

            $response->getBody()->write(
                $this->twig->render('controller/keys/keys.twig', [
                    'errors' => $errors,
                ])
            );

            return $response;
        }

        if ('type' == $attrs['action']) {
            return $this->handleType(
                $this->redis,
                $this->urlBuilder,
                $response,
                $this->twig,
                $attrs
            );
        }
    }
}
