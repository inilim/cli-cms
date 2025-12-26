<?php

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
        $connect->connect();
        $pdo = $connect->getPDO();

        // INFO CRC_32 возвращаем как строку, а то sqlite плохо воспринимает большие числа
        $pdo->sqliteCreateFunction('CRC_32', static fn($value): string => (string)\crc32((string)$value), 1);
        $pdo->sqliteCreateFunction('UNIX_MS', Time::__asClosure('unixMs'), 0);
    }
}
