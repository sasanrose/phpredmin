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
    use Traits\Keys\Keys;

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

        $errors = $this->validate($attrs);

        if (!empty($errors)) {
            $response->getBody()->write(
                $this->twig->render('controller/keys/keys.twig', [
                    'errors' => $errors,
                ])
            );

            return $response;
        }

        $method = 'handleType';

        if ($attrs['action'] === 'keys') {
            $method = 'handleKeys';
        }

        return $this->$method(
            $this->redis,
            $this->urlBuilder,
            $response,
            $this->twig,
            current((array) $attrs['keys'])
        );
    }

    /**
     * Validates keys and action.
     *
     * @param array $attrs
     *
     * @return array
     */
    protected function validate(array $attrs): array {
        if (!isset($attrs['keys']) || empty($attrs['keys'])) {
            return [
                'keys' => _('Key is required'),
            ];
        }

        if (!isset($attrs['action']) || empty($attrs['action'])) {
            return [
                'action' => _('Action is required'),
            ];
        }

        if (!in_array($attrs['action'], ['type', 'keys', 'scan'])) {
            return [
                'action' => _('Invalid action'),
            ];
        }

        return [];
    }
}
