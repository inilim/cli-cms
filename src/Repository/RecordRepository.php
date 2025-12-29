<?php

declare(strict_types=1);

namespace App\Repository;

use Inilim\Tool\Arr;
use Inilim\Tool\Time;
use Inilim\Tool\Assert;
use Inilim\Tool\LarArr;
use App\Entity\RecordEntity;
use App\Entity\CategoryEntity;
use App\Repository\CategoryRepository;
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
            WHERE created_at_ms <= UNIX_MS()
            ORDER BY created_at_ms DESC
            LIMIT {offset},{limit}';

        /** @var array<array{id: string, category_id: int, body: string|null, short_body: string|null, seo_title: string|null, created_at_ms: int}> $records */
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

        /** @var array<array{id: string, category_id: int, body: string|null, short_body: string|null, seo_title: string|null, created_at_ms: int}> $records */
        $records = $this->connect->exec($sql, [
            'limit'      => $limit,
            'offset'     => $offset,
        ], 2);

        $result = [];
        if (!$records) {
            return $result;
        }


        // Собираем все ID категорий
        $categoryIds = \array_column($records, 'category_id');
        $records = \array_map(RecordEntity::fromArray(...), $records);
        $categoryIds = \array_filter($categoryIds, static fn($id) => $id !== null);
        $categoryIds = Arr::unique($categoryIds);

        // Получаем все категории за один запрос
        $categories = [];
        if ($categoryIds) {
            $sql = 'SELECT * FROM categories WHERE id IN ({categoryIds})';
            $categoryData = $this->connect->exec($sql, ['categoryIds' => $categoryIds], 2);
            foreach ($categoryData as $category) {
                $categories[$category['id']] = CategoryEntity::fromArray($category);;
            }
            unset($categoryData);
        }
        unset($categoryIds);

        // Связываем записи с категориями
        foreach ($records as $record) {
            $category = $record->categoryId !== null ? $categories[$record->categoryId] ?? null : null;
            $result[] = RecordWithCategoryEntity::from($record, $category);
        }

        return $result;
    }
}
