<?php

declare(strict_types=1);

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

        // Если у записи есть категория, получаем её
        if ($record->category_id !== null) {
            $category = \DI(\App\Repository\CategoryRepository::class)->findByID($record->category_id);
            if ($category) {
                $record->setCategory($category);
            }
        }

        // Обрабатываем запись, преобразуя JSON-тело в структурированные данные
        $processedBlocks = $blockProcessingService->processBody($record->body ?? '');
        $processedRecord = [
            'id'            => $record->id,
            'category_id'   => $record->category_id,
            'category'      => $record->category,
            'seo_title'     => $record->seo_title,
            'blocks'        => $processedBlocks,
            'created_at_ms' => $record->created_at_ms
        ];

        // Рендерим страницу записи
        $twigRenderService->show('record_page', [
            'record' => $processedRecord
        ]);
    }
}
