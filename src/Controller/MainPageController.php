<?php

namespace App\Controller;

use Inilim\Tool\VD;
use App\Service\BlockProcessingService;
use App\Service\TwigRenderService;
use App\Repository\RecordRepository;

/**
 */
final class MainPageController extends \App\Controller\ControllerAbstract
{
    function __invoke()
    {
        // Получаем зависимости через DI-контейнер
        $recordRepository  = \DI(RecordRepository::class);
        $twigRenderService = \DI(TwigRenderService::class);
        $blockProcessingService = \DI(BlockProcessingService::class);

        // Получаем записи для главной страницы как сущности
        $records = $recordRepository->getForMainPage();

        // Обрабатываем каждую запись, преобразуя JSON-тело в структурированные данные
        $processedRecords = [];
        foreach ($records as $record) {
            $processedBlocks = $blockProcessingService->processBody($record->body);
            $processedRecords[] = [
                'id'            => $record->id,
                'category_id'   => $record->categoryId,
                'blocks'        => $processedBlocks,
                'created_at_ms' => $record->createdAtMs
            ];
        }

        // Рендерим главную страницу
        $twigRenderService->show('main_page', [
            'records' => $processedRecords
        ]);
    }
}
