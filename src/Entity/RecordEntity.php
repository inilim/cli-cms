<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * Сущность для представления записи из репозитория RecordRepository
 */
final class RecordEntity
{
    /**
     * @param string $id Уникальный идентификатор записи (UUIDv7)
     * @param int $categoryId Идентификатор категории, к которой принадлежит запись
     * @param string|null $body Тело записи в формате JSON
     * @param string|null $shortBody Краткое содержимое для отображения на главной странице
     * @param string|null $seoTitle Заголовок для тега title (SEO заголовок)
     * @param int $createdAtMs Время создания записи в миллисекундах (Unix timestamp)
     */
    function __construct(
        protected(set) string $id,
        protected(set) int $categoryId,
        protected(set) ?string $body,
        protected(set) ?string $shortBody,
        protected(set) ?string $seoTitle,
        protected(set) int $createdAtMs,
    ) {}

    /**
     * @param array{id: string, category_id: int, body: string|null, short_body: string|null, seo_title: string|null, created_at_ms: int} $record
     */
    static function fromArray(array $record): self
    {
        return new self(
            $record['id'],
            $record['category_id'],
            $record['body'] ?? null,
            $record['short_body'] ?? null,
            $record['seo_title'] ?? null,
            $record['created_at_ms']
        );
    }
}
