<?php

namespace App\Repository;

use App\Exception\AppException;
use Inilim\Tool\Time;
use Inilim\IPDO\IPDOSQLite;

abstract class RepositoryAbstract
{
    protected IPDOSQLite $connect;

    function __construct()
    {
        $connect = \DITag('db');
        if ($connect === null) {
            throw new AppException('Database connection is not available');
        }
        /** @var IPDOSQLite $connect */
        $this->connect = $connect;
    }

    /**
     * @param array<array-key, bool|float|int|string> $values
     */
    protected function execExists(string $sql, array $values = []): bool
    {
        $sql = \sprintf('SELECT exists (%s) as ex', $sql);
        $result = $this->connect->exec($sql, $values, 1);
        $exists = $result['ex'] ?? '0';
        return $exists == '1' ? true : false;
    }
}
