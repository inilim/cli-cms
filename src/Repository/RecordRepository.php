<?php

namespace App\Repository;

use Inilim\Tool\Time;
use Inilim\Tool\Assert;
use Inilim\IPDO\IPDOSQLite;
use App\Repository\RepositoryAbstract;

// use @psalm-import-type Record from \App\Repository\RecordRepository

/**
 * @psalm-type Record = array{id:string,body:string,created_at_ms:int}
 */
final class RecordRepository extends RepositoryAbstract
{
    function __construct()
    {
        $config = \DITag('config');
        /** @var Env $config */
        $this->connect = new IPDOSQLite($config->getStr('db_dir') . '/records.sqlite');
        parent::__construct();
    }

    /**
     * @return ?Record
     */
    function findByID(string $id): ?array
    {
        $sql = 'SELECT * FROM records WHERE id = {id}';
        $record = $this->connect->exec($sql, ['id' => $id], 1);
        return $record ? $record : null;
    }

    /**
     * @return Record[]
     */
    function getForMainPage(int $limit = 10, int $offset = 0): array
    {
        Assert::positiveInteger($limit);
        Assert::natural($offset);

        $sql = 'SELECT * FROM records
            WHERE created_at_ms <= {current_ms}
            ORDER BY created_at_ms DESC
            LIMIT {offset},{limit}';

        return $this->connect->exec($sql, [
            'limit'      => $limit,
            'offset'     => $offset,
            'current_ms' => Time::unixMs(),
        ], 2);
    }
}
