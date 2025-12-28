<?php

declare(strict_types=1);

namespace App;

use App\Logger;
use Inilim\Tool\Obj;
use Inilim\Tool\Time;
use Inilim\Tool\Check;
use Inilim\Tool\Other;
use Inilim\Tool\LarArr;

final class ErrorHandler
{
    protected Logger $logger;

    function __construct()
    {
        $this->logger = \DI(Logger::class);
    }

    function register(): void
    {
        \set_error_handler(self::handleError(...));
        \set_exception_handler(self::handleException(...));
        // \register_shutdown_function(self::shutdown(...));
    }

    // ------------------------------------------------------------------
    // 
    // ------------------------------------------------------------------

    protected function shutdown(): void
    {
        // @INFO set_error_handler не обрабатывает ряд ошибок, и происходит обрыв выполнения скрипта, но register_shutdown_function отрабатывает...

        $err = \error_get_last();
        if (!$err) {
            return;
        }

        $log = [
            'shutdown_ms' => Time::unixMs(),
            'err' => $err,
        ];

        $this->logger('SHUTDOWN', $log);
    }

    /**
     * @param integer $level_err
     * @param string $message
     * @param string $file
     * @param integer $line
     * @return boolean
     */
    protected function handleError($level_err, $message, $file, $line)
    {
        $t = [
            'error_reporting' => $t = \error_reporting(),
            'level_err'       => $level_err,
            '@suppress'       => !($t & $level_err),
            'message'         => $message,
            'file'            => $file,
            'line'            => $line,
            'ms'              => Time::unixMs(),
        ];

        if ($t['@suppress']) {
            $this->logger('@suppress', $t);
            return true;
        }

        if (\in_array($level_err, [\E_DEPRECATED, \E_USER_DEPRECATED], true)) {
            $this->logger('E_DEPRECATED', $t);
        } elseif ($level_err === \E_NOTICE) {
            $this->logger('E_NOTICE', $t);
        } else {
            throw Obj::rewriteLocationException(new \Error($message), $file, $line);
        }

        // Не запускаем внутренний обработчик ошибок PHP
        return true;
    }

    /**
     * @param \Throwable $e
     */
    protected function handleException($e): void
    {
        if (Check::arrLike($e)) {
            $arr = Obj::unpuckTraversableRecursive($e);
        } else {
            $arr = Other::getExceptionDetails($e);
        }

        $this->logger('EXCEPTION', $arr);
        throw $e;
    }

    /**
     * @param array<mixed> $data
     */
    protected function logger(string $name, array $data): void
    {
        $data = [
            'LOG' => $data,
            'INFO' => [
                'NAME_TRIGGER' => $name,
                '_SERVER' => LarArr::only($_SERVER, [
                    'SCRIPT_FILENAME',
                    'REQUEST_TIME_FLOAT',
                ]),
            ],
        ];

        $this->logger->__invoke($data);
    }
}
