<?php

declare(strict_types=1);

namespace App\Repository;

use Inilim\Tool\Arr;
use Inilim\Tool\Assert;
use App\Entity\RecordEntity;
use App\Entity\CategoryEntity;
use App\Repository\RepositoryAbstract;
use App\Entity\RecordWithCategoryEntity;

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
        /** @var array{id: string, category_id: ?int, body: string|null, short_body: string|null, seo_title: string|null, created_at_ms: int}|array{} $record */
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
            WHERE created_at_ms <= UNIX_MS()
            ORDER BY created_at_ms DESC
            LIMIT {offset},{limit}';

        /** @var array<array{id: string, category_id: ?int, body: string|null, short_body: string|null, seo_title: string|null, created_at_ms: int}> $records */
        $records = $this->connect->exec($sql, [
            'limit'      => $limit,
            'offset'     => $offset,
        ], 2);

        return \array_map(RecordEntity::fromArray(...), $records);
    }

    /**
     * @return \App\Entity\RecordWithCategoryEntity[]
     */
    function getForMainPageWithCategory(int $limit = 10, int $offset = 0): array
    {
        Assert::positiveInteger($limit);
        Assert::natural($offset);

        $sql = 'SELECT * FROM records
            WHERE created_at_ms <= UNIX_MS()
            ORDER BY created_at_ms DESC
            LIMIT {offset},{limit}';

        /** @var array<array{id: string, category_id: ?int, body: string|null, short_body: string|null, seo_title: string|null, created_at_ms: int}> $records */
        $records = $this->connect->exec($sql, [
            'limit'      => $limit,
            'offset'     => $offset,
        ], 2);

        if (!$records) {
            return [];
        }

        // Собираем все ID категорий
        // Приводим все category_id к int и фильтруем
        $categoryIds = Arr::mapFilter($records, static function (array $record): ?int {
            $id = $record['category_id'];
            if ($id === null) {
                return null;
            }
            return $id;
        });
        /** @var int[] $categoryIds */
        $categoryIds = Arr::unique($categoryIds);

        // Получаем все категории за один запрос
        $categories = [];
        if ($categoryIds) {
            $sql = 'SELECT * FROM categories WHERE id IN ({categoryIds})';
            $categoryData = $this->connect->exec($sql, ['categoryIds' => $categoryIds], 2);
            /** @var (array{id:int,name:string})[] $categoryData */
            foreach ($categoryData as $idx => $category) {
                $categories[$category['id']] = CategoryEntity::fromArray($category);
                unset($categoryData[$idx]);
            }
            unset($categoryData);
        }
        unset($categoryIds);

        $result = [];
        // Связываем записи с категориями
        foreach ($records as $idx => $record) {
            $categoryId = $record['category_id'];
            $category = $categories[$categoryId ?? -1] ?? null;
            $result[] = RecordWithCategoryEntity::from(
                RecordEntity::fromArray($record),
                $category
            );
            unset($records[$idx]);
        }

        return $result;
    }
}
