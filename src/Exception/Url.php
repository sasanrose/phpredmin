<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Exception;

/**
 * Url Exception is intended for url building or parsing exceptions.
 */
class Url extends \Exception
{
    /**
     * Details of the url.
     *
     * @var array|string
     */
    protected $url;

    /**
     * Constructor for url Exceptions.
     *
     * @param string       $message
     * @param array|string $url
     * @param int          $code
     * @param \Exception   $previous
     */
    public function __construct($message, $url, $code = 0, \Exception $previous = NULL)
    {
        $this->url = $url;

        parent::__construct($message, $code, $previous);
    }

    /**
     * Returns the details of the url that caused this exception.
     *
     * @return array|string
     */
    public function getUrlDetails()
    {
        return $this->url;
    }
}
