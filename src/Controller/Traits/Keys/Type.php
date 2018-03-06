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

trait Type
{
    use Actions;

    /**
     * Handles the type action in key controller.
     *
     * @param PhpRedminRedis      $redis
     * @param UrlBuilderInterface $urlBuilder
     * @param ResponseInterface   $response
     * @param Environment         $twig
     * @param array               $attrs
     *
     * @return ResponseInterface
     */
    protected function handleType(
        PhpRedminRedis $redis,
        UrlBuilderInterface $urlBuilder,
        ResponseInterface $response,
        Environment $twig,
        array $attrs
    ): ResponseInterface
    {
        $key = current($attrs['keys']);

        $type = $redis->type($key);

        if ($type === PhpRedminRedis::REDIS_NOT_FOUND) {
            $response->getBody()->write(
                $twig->render('controller/keys/not-found.twig', ['search' => $key])
            );

            return $response;
        }

        if (isset($this->actions[$type])) {
            $serverIndex = $redis->getServerIndex();
            $dbIndex = $redis->getDbIndex();

            // Sending the action as a query is just for checking the access
            // levels of current user to check if the user has access to
            // trigger the requested action or not
            $urlBuilder->setRedis(
                $serverIndex,
                $dbIndex,
                $this->actions[$type],
                [$key]
            );

            $urlBuilder->setQuery(['keyType' => $type]);
            $urlBuilder->setPath('view');

            return $response->withRedirect($urlBuilder->toString());
        }

        $response->getBody()->write(
            $twig->render('controller/keys/unknown-type.twig', ['search' => $key])
        );

        return $response;
    }
}
