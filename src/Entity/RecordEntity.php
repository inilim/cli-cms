<?php

namespace App\Entity;

/**
 * Сущность для представления записи из репозитория RecordRepository
 */
final class RecordEntity
{
    /**
     * @param string $id Уникальный идентификатор записи (UUIDv7)
     * @param int $categoryId Идентификатор категории, к которой принадлежит запись
     * @param string $body Тело записи в формате JSON
     * @param int $createdAtMs Время создания записи в миллисекундах (Unix timestamp)
     */
    function __construct(
        protected(set) string $id,
        protected(set) int $categoryId,
        protected(set) string $body,
        protected(set) int $createdAtMs,
    ) {}

    static function fromArray(array $record): self
    {
        return new self(
            $record['id'],
            $record['category_id'],
            $record['body'],
            $record['created_at_ms']
        );
    }
}
