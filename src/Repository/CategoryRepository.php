<?php

declare(strict_types=1);

namespace App\Repository;

use Inilim\Tool\Time;
use Inilim\Tool\Assert;
use App\Entity\CategoryEntity;
use App\Repository\RepositoryAbstract;

/**
 */
final class CategoryRepository extends RepositoryAbstract
{
    /**
     * @return CategoryEntity|null
     */
    function findByID(int $id): ?CategoryEntity
    {
        Assert::positiveInteger($id);

        $sql = 'SELECT * FROM categories WHERE id = {id}';
        /** @var array{id: int, name: string}|array{} $category */
        $category = $this->connect->exec($sql, ['id' => $id], 1);
        return $category ? CategoryEntity::fromArray($category) : null;
    }
}
