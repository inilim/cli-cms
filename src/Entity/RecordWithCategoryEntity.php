<?php

namespace App\Entity;

use App\Entity\RecordEntity;
use App\Entity\CategoryEntity;

final class RecordWithCategoryEntity
{
    function __construct(
        protected(set) RecordEntity $record,
        protected(set) CategoryEntity $category,
    ) {}
}
