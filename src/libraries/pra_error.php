<?php

/*
 * This file is part of the "PHP Redis Admin" package.
 *
 * (c) Faktiva (http://faktiva.com)
 *
 * NOTICE OF LICENSE
 * This source file is subject to the CC BY-SA 4.0 license that is
 * available at the URL https://creativecommons.org/licenses/by-sa/4.0/
 *
 * DISCLAIMER
 * This code is provided as is without any warranty.
 * No promise of being safe or secure
 *
 * @author   Sasan Rose <sasan.rose@gmail.com>
 * @author   Emiliano 'AlberT' Gabrielli <albert@faktiva.com>
 * @license  https://creativecommons.org/licenses/by-sa/4.0/  CC-BY-SA-4.0
 * @source   https://github.com/faktiva/php-redis-admin
 */

final class PRA_Error
{
    public function __construct()
    {
        ini_set('display_errors', 0);
        if (App::instance()->config['debug']) {
            ini_set('display_errors', 1);
        }

        // Set a custom error Handler
        set_error_handler(array($this, 'errorHandler'));
        // Set a custom Exception Handler
        set_exception_handler(array($this, 'exceptionHandler'));
        // Set Shutdown Handler
        register_shutdown_function(array($this, 'shutdownHandler'));
    }

    public static function shutdownHandler()
    {
        $error = error_get_last();

        if ($error) {
            $type = self::_getError($error['type']);
            Log::factory()->write($type, "{$error['message']} on {$error['file']}:{$error['line']}");
        }
    }

    public function exceptionHandler(Exception $e)
    {
        Log::factory()->write(Log::ERROR, "{$e->getMessage()} on {$e->getFile()}:{$e->getLine()}");
    }

    public function errorHandler($no, $str, $file, $line, $context)
    {
        if (!(error_reporting() & $no)) {
            // This error code is not included in error_reporting
            return;
        }

        $type = self::_getError($no);

        Log::factory()->write($type, "{$str} on {$file}:{$line}");
    }

    protected static function _getError($type)
    {
        switch ($type) {
            case E_WARNING: // 2 //
                return Log::WARNING;
            case E_NOTICE: // 8 //
                return Log::NOTICE;
            case E_CORE_WARNING: // 32 //
                return Log::WARNING;
            case E_USER_WARNING: // 512 //
                return Log::WARNING;
            case E_USER_NOTICE: // 1024 //
                return Log::NOTICE;
            case E_DEPRECATED: // 8192 //
                return Log::WARNING;
            case E_USER_DEPRECATED: // 16384 //
                return Log::WARNING;
        }

        return Log::ERROR;
    }
}
