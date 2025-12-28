<?php

namespace App\Controller;

use App\Exception\AppException;
use Inilim\Tool\VD;
use App\Service\BlockProcessingService;
use App\Service\TwigRenderService;
use App\Repository\RecordRepository;

/**
 * Контроллер для отображения отдельной страницы записи
 */
final class RecordPageController extends \App\Controller\ControllerAbstract
{
    function __invoke(string $recordId): void
    {
        // Получаем зависимости через DI-контейнер
        $recordRepository  = \DI(RecordRepository::class);
        $twigRenderService = \DI(TwigRenderService::class);
        $blockProcessingService = \DI(BlockProcessingService::class);

        // Получаем запись по ID
        $record = $recordRepository->findByID($recordId);

        if (!$record) {
            throw new AppException(\sprintf('Record id "%s" not found', $recordId));
        }

        // Обрабатываем запись, преобразуя JSON-тело в структурированные данные
        $processedBlocks = $blockProcessingService->processBody($record->body ?? '');
        $processedRecord = [
            'id'            => $record->id,
            'category_id'   => $record->categoryId,
            'seo_title'      => $record->seoTitle,
            'blocks'        => $processedBlocks,
            'created_at_ms' => $record->createdAtMs
        ];

        // Рендерим страницу записи
        $twigRenderService->show('record_page', [
            'record' => $processedRecord
        ]);
    }
}
