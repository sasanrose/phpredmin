<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Url\Builder;

use http\Url;
use PhpRedmin\Exception\Url as UrlException;
use PhpRedmin\Url\UrlBuilderInterface;

class Pecl implements UrlBuilderInterface
{
    /**
     * Hostname.
     *
     * @var string
     */
    protected $host;

    /**
     * Http scheme.
     *
     * @var string
     */
    protected $scheme = 'http';

    /**
     * Request path.
     *
     * @var string
     */
    protected $path;

    /**
     * Query strings.
     *
     * @var string
     */
    protected $query;

    /**
     * {@inheritdoc}
     */
    public function setHost(string $host): UrlBuilderInterface
    {
        $this->host = $host;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setScheme(string $scheme): UrlBuilderInterface
    {
        $this->scheme = $scheme;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setPath(string $path): UrlBuilderInterface
    {
        $this->path = $path;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setQuery(array $query): UrlBuilderInterface
    {
        $this->query = http_build_query($query);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        $data = [
            'host' => $this->host,
            'scheme' => $this->scheme,
            'path' => $this->path,
            'query' => $this->query,
        ];

        if (!isset($this->host)) {
            throw new UrlException('Host is required', $data);
        }

        $url = new Url($data);

        return $url->toString();
    }
}
