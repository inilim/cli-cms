<?php

namespace App\Repository;

use Inilim\Tool\Time;
use Inilim\IPDO\IPDOSQLite;

abstract class RepositoryAbstract
{
    protected IPDOSQLite $connect;

    function __construct()
    {
        // 
    }

    function execExists(string $sql, array $values = []): bool
    {
        $sql = \sprintf('SELECT exists (%s) as ex', $sql);
        $exists = $this->connect->exec($sql, $values, 1)['ex'] ?? '0';
        return $exists == '1' ? true : false;
    }


    // ------------------------------------------------------------------
    // 
    // ------------------------------------------------------------------

    protected function initDbFunctions(): void
    {
        $this->connect->connect();
        $pdo = $this->connect->getPDO();

        // INFO CRC_32 возвращаем как строку, а то sqlite плохо воспринимает большие числа
        $pdo->sqliteCreateFunction('CRC_32', static fn($value): string => (string)\crc32((string)$value), 1);
        $pdo->sqliteCreateFunction('UNIX_MS', Time::__asClosure('unixMs'), 0);
    }
}
