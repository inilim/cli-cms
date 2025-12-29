<?php

declare(strict_types=1);

namespace App\Controller;

use Inilim\Tool\VD;
use App\Service\BlockProcessingService;
use App\Service\TwigRenderService;
use App\Repository\RecordRepository;

/**
 */
final class MainPageController extends \App\Controller\ControllerAbstract
{
    function __invoke(): void
    {
        // Получаем зависимости через DI-контейнер
        $recordRepository  = \DI(RecordRepository::class);
        $twigRenderService = \DI(TwigRenderService::class);
        $blockProcessingService = \DI(BlockProcessingService::class);

        // Получаем записи для главной страницы вместе с категориями
        $recordsWithCategory = $recordRepository->getForMainPageWithCategory();

        // Обрабатываем каждую запись, преобразуя JSON-тело в структурированные данные
        $processedRecords = [];
        foreach ($recordsWithCategory as $recordWithCategory) {
            $processedBlocks = $blockProcessingService->processBody($recordWithCategory->record->shortBody);
            $processedRecords[] = [
                'recordWithCategory' => $recordWithCategory,
                'blocks'             => $processedBlocks
            ];
        }

        // Рендерим главную страницу
        $twigRenderService->show('main_page', [
            'records' => $processedRecords
        ]);
    }
}
