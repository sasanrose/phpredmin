<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin;

use Pimple\Container;
use Psr\Log\LoggerInterface;
use Zend\Log\Filter;
use Zend\Log\Logger;
use Zend\Log\PsrLoggerAdapter;
use Zend\Log\Writer\Stream;

/**
 * @SuppressWarnings(StaticAccess)
 */
function logger(Container $container)
{
    // Logger
    $container[LoggerInterface::class] = function ($c) {
        $filter = new Filter\Priority((int) $c['LOG_LEVEL']);

        $writer = new Stream('php://stderr');
        $writer->addFilter($filter);

        // Initialize the logger
        $zendLogger = new Logger();
        $zendLogger->addWriter($writer);

        // Register logging system as an error handler to log PHP errors
        Logger::registerErrorHandler($zendLogger);

        // Wrapper class to ensure the logger is PSR-3 compatible
        return new PsrLoggerAdapter($zendLogger);
    };

    return $container;
}
