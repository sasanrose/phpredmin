<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Url;

interface UrlBuilderInterface
{
    /**
     * Sets the http host for a url.
     *
     * @param string $host
     *
     * @return UrlBuilderInterface
     */
    public function setHost(string $host): UrlBuilderInterface;

    /**
     * Sets the http scheme for a url.
     *
     * @param string $scheme
     *
     * @return UrlBuilderInterface
     */
    public function setScheme(string $scheme): UrlBuilderInterface;

    /**
     * Sets the path for a url.
     *
     * @param string $path
     *
     * @return UrlBuilderInterface
     */
    public function setPath(string $path): UrlBuilderInterface;

    /**
     * Sets the query string of a url. Accepts a map of key, values.
     *
     * @param array $query
     *
     * @return UrlBuilderInterface
     */
    public function setQuery(array $query): UrlBuilderInterface;

    /**
     * Returns the string representation of the URL.
     *
     * @return string
     */
    public function toString(): string;
}
