<?php

namespace App\Repository;

use Inilim\Tool\Time;
use Inilim\Tool\Assert;
use App\Repository\RepositoryAbstract;

// use @psalm-import-type Category from \App\Repository\CategoryRepository

/**
 * @psalm-type Category = array{id:int,name:string}
 */
final class CategoryRepository extends RepositoryAbstract
{
    /**
     * @return ?Category
     */
    function findByID(int $id): ?array
    {
        Assert::positiveInteger($id);

        $sql = 'SELECT * FROM categories WHERE id = {id}';
        $catagory = $this->connect->exec($sql, ['id' => $id], 1);
        return $catagory ? $catagory : null;
    }
}
