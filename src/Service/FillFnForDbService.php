<?php

declare(strict_types=1);

namespace App\Service;

use Inilim\Tool\VD;
use Inilim\Tool\Time;
use Inilim\IPDO\IPDOSQLite;

/**
 * Инициализируем функции для sqlite соединения
 */
final class FillFnForDbService
{
    function __invoke(IPDOSQLite $connect): void
    {
        // INFO CRC_32 возвращаем как строку, а то sqlite плохо воспринимает большие числа
        $connect->createFunction('CRC_32', static function ($value): string {
            return (string)\crc32((string)$value);
        }, 1);
        $cls = Time::__asClosure('unixMs');
        /** @var \Closure $cls */
        $connect->createFunction('UNIX_MS', $cls, 0);
    }
}
