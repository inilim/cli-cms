<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\RecordEntity;
use App\Service\BlockProcessingService;

/**
 * Сервис для обработки short_body записей
 */
final class RecordShortBodyProcessingService
{
    /**
     * Обрабатывает массив записей, преобразуя JSON-тело в структурированные данные
     * и встраивая их в свойство blocks
     *
     * @param RecordEntity[] $records
     * @return RecordEntity[]
     */
    function processRecords(array $records): array
    {
        $blockProcessingService = \DI(BlockProcessingService::class);
        foreach ($records as $record) {
            $record->setProp(
                'blocks',
                $blockProcessingService->processBody($record->short_body)
            );
            $record->removeProps(['short_body', 'body']);
        }

        return $records;
    }

    /**
     * Обрабатывает отдельную запись, преобразуя JSON-тело в структурированные данные
     * и встраивая их в свойство blocks
     *
     * @param RecordEntity $record
     */
    function processRecord(RecordEntity $record): void
    {
        $this->processRecords([$record]);
    }
}
