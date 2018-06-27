<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Controller\Traits\Keys;

use PhpRedmin\Redis as PhpRedminRedis;
use PhpRedmin\Url\UrlBuilderInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment;

trait Keys
{
    /**
     * Handles the keys action in key controller.
     *
     * @param PhpRedminRedis      $redis
     * @param UrlBuilderInterface $urlBuilder
     * @param ResponseInterface   $response
     * @param Environment         $twig
     * @param string              $key
     *
     * @return ResponseInterface
     */
    protected function handleKeys(
        PhpRedminRedis $redis,
        UrlBuilderInterface $urlBuilder,
        ResponseInterface $response,
        Environment $twig,
        string $key
    ): ResponseInterface {
        $result = $redis->keys($key);

        if (!$result) {
            $response->getBody()->write(
                $twig->render('controller/keys/keys.twig', [
                    'search' => $key,
                    'notFound' => TRUE,
                ])
            );

            return $response;
        }
    }
}
