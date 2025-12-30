<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Attribute\AdditionallyAttr;

/**
 * Сущность для представления записи из репозитория RecordRepository
 */
final class RecordEntity extends \App\Entity\DynamicEntityAbstract
{
    protected(set) ?string $id = null;
    protected(set) ?int $category_id = null;
    protected(set) ?string $body = null;
    protected(set) ?string $short_body = null;
    protected(set) ?string $seo_title = null;
    protected(set) ?int $created_at_ms = null;

    #[AdditionallyAttr]
    protected(set) ?CategoryEntity $category = null;

    function setCategory(CategoryEntity $category): self
    {
        if ($this->category_id === null) {
            throw new \InvalidArgumentException(\sprintf('Cannot set category for record with null category_id'));
        }

        if ($category->id !== $this->category_id) {
            throw new \InvalidArgumentException(\sprintf('Category ID mismatch: expected %s, got %s', $this->category_id, $category->id));
        }
        $this->setProp('category', $category);

        return $this;
    }
}
