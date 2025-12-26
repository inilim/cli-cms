<?php

namespace App\Repository;

use Inilim\Tool\Time;
use Inilim\IPDO\IPDOSQLite;

abstract class RepositoryAbstract
{
    protected IPDOSQLite $connect;

    function __construct()
    {
        $this->connect = \DITag('db');
    }

    function execExists(string $sql, array $values = []): bool
    {
        $sql = \sprintf('SELECT exists (%s) as ex', $sql);
        $exists = $this->connect->exec($sql, $values, 1)['ex'] ?? '0';
        return $exists == '1' ? true : false;
    }
}
