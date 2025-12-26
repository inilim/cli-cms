<?php

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
     */
    function findByID(int $id): ?CategoryEntity
    {
        Assert::positiveInteger($id);

        $sql = 'SELECT * FROM categories WHERE id = {id}';
        $catagory = $this->connect->exec($sql, ['id' => $id], 1);
        return $catagory ? CategoryEntity::fromArray($catagory) : null;
    }
}
