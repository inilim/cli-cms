<?php

declare(strict_types=1);

namespace App\Repository;

use Inilim\Tool\Arr;
use Inilim\Tool\Assert;
use App\Entity\RecordEntity;
use App\Entity\CategoryEntity;
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

        return RecordEntity::fromArrayAll($records);
    }

    /**
     * @return RecordEntity[]
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
            // Создаем параметры для IN-запроса
            $sql = "SELECT * FROM categories WHERE id IN ({list})";
            // @phpstan-ignore-next-line
            $categories = $this->connect->exec($sql, [
                'list' => $categoryIds
            ], 2);
            /** @var (array{id:int,name:string})[] $categories */
            $categories = \array_column($categories, null, 'id');
        }
        unset($categoryIds);

        // Связываем записи с категориями
        $records = RecordEntity::fromArrayAll($records);
        foreach ($records as $record) {
            $categoryId = $record->category_id;
            $category = $categories[$categoryId ?? -1] ?? null;
            /** @var array{id:int,name:string}|null $category */
            if ($category) {
                $record->setCategory(CategoryEntity::fromArray($category));
            }
        }

        return $records;
    }
}
