<?php

declare(strict_types=1);

namespace App\Entity;

use App\Exception\AppException;

/**
 * Сущность для представления записи вместе с её категорией
 */
final class RecordWithCategoryEntity
{
    /**
     * @param RecordEntity $record Запись
     * @param ?CategoryEntity $category Категория (может быть null, если категория не найдена)
     */
    protected(set) RecordEntity $record;
    protected(set) ?CategoryEntity $category;

    /**
     * Создает сущность RecordWithCategoryEntity из записи и опциональной категории
     *
     * @param RecordEntity $record Запись
     * @param ?CategoryEntity $category Категория (может быть null, если категория не найдена)
     * @return self
     * @throws AppException Если ID категории записи не совпадает с ID переданной категории
     */
    static function from(RecordEntity $record, ?CategoryEntity $category): self
    {
        if ($category !== null && $record->categoryId !== $category->id) {
            throw new AppException(\sprintf(
                'Record category ID (%d) does not match provided category ID (%d)',
                $record->categoryId,
                $category->id
            ));
        }

        $self = new self;
        $self->record = $record;
        $self->category = $category;
        return $self;
    }
}
