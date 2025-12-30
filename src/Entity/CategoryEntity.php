<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * Сущность для представления категории из репозитория CategoryRepository
 */
final class CategoryEntity extends \App\Entity\DynamicEntityAbstract
{
    protected(set) ?int $id = null;
    protected(set) ?string $name = null;
}
