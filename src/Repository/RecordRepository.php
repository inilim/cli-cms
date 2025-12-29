<?php

declare(strict_types=1);

namespace App\Repository;

use Inilim\Tool\Time;
use Inilim\Tool\Assert;
use App\Entity\RecordEntity;
use App\Repository\RepositoryAbstract;

/**
 */
final class RecordRepository extends RepositoryAbstract
{
    /**
     * @return RecordEntity|null
     */
    function findByID(string $id): ?RecordEntity
    {
        $sql = 'SELECT * FROM records WHERE id = {id}';
        /** @var array{id: string, category_id: int, body: string|null, short_body: string|null, seo_title: string|null, created_at_ms: int}|array{} $record */
        $record = $this->connect->exec($sql, ['id' => $id], 1);
        return $record ? RecordEntity::fromArray($record) : null;
    }

    /**
     * @return RecordEntity[]
     */
    function getForMainPage(int $limit = 10, int $offset = 0): array
    {
        Assert::positiveInteger($limit);
        Assert::natural($offset);

        $sql = 'SELECT * FROM records
            WHERE created_at_ms <= {current_ms}
            ORDER BY created_at_ms DESC
            LIMIT {offset},{limit}';

        /** @var array<array{id: string, category_id: int, body: string|null, short_body: string|null, seo_title: string|null, created_at_ms: int}> $records */
        $records = $this->connect->exec($sql, [
            'limit'      => $limit,
            'offset'     => $offset,
            'current_ms' => Time::unixMs(),
        ], 2);

        return \array_map(RecordEntity::fromArray(...), $records);
    }
}
