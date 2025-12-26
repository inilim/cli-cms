<?php

namespace App\Controller;

use Inilim\Tool\VD;
use App\Service\BlockProcessingService;
use App\Service\TwigRenderService;
use App\Repository\RecordRepository;

/**
 * @psalm-import-type Record from \App\Repository\RecordRepository
 */
final class MainPageController extends \App\Controller\ControllerAbstract
{
    function __invoke()
    {
        // Получаем зависимости через DI-контейнер
        $recordRepository  = \DI(RecordRepository::class);
        $twigRenderService = \DI(TwigRenderService::class);
        $blockProcessingService = \DI(BlockProcessingService::class);

        // Получаем записи для главной страницы
        $records = $recordRepository->getForMainPage();

        // Обрабатываем каждую запись, преобразуя JSON-тело в структурированные данные
        $processedRecords = [];
        foreach ($records as $record) {
            $processedBlocks = $blockProcessingService->processBody($record['body']);
            $processedRecords[] = [
                'id'            => $record['id'],
                'category_id'   => $record['category_id'],
                'blocks'        => $processedBlocks,
                'created_at_ms' => $record['created_at_ms']
            ];
        }

        // Рендерим главную страницу
        $twigRenderService->show('main_page', [
            'records' => $processedRecords
        ]);
    }
}
