<?php

namespace App\Entity;

/**
 * Сущность для представления категории из репозитория CategoryRepository
 */
final class CategoryEntity
{
    /**
     * @param int $id Уникальный идентификатор категории
     * @param string $name Название категории
     */
    function __construct(
        protected(set) int $id,
        protected(set) string $name,
    ) {}

    /**
     * @param array{id: int, name: string} $category
     */
    static function fromArray(array $category): self
    {
        return new self(
            $category['id'],
            $category['name']
        );
    }
}
