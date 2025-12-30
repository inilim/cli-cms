<?php

declare(strict_types=1);

namespace App\Controller;

use Inilim\Tool\VD;
use App\Service\BlockProcessingService;
use App\Service\TwigRenderService;
use App\Repository\RecordRepository;
use App\Service\RecordBlockProcessingService;

/**
 */
final class MainPageController extends \App\Controller\ControllerAbstract
{
    function __invoke(): void
    {
        // Получаем зависимости через DI-контейнер
        $recordRepository  = \DI(RecordRepository::class);
        $twigRenderService = \DI(TwigRenderService::class);
        $recordBlockProcessingService = \DI(RecordBlockProcessingService::class);

        // Получаем записи для главной страницы вместе с категориями
        $records = $recordRepository->getForMainPageWithCategory();

        // Обрабатываем записи, преобразуя JSON-тело в структурированные данные
        $records = $recordBlockProcessingService->processRecords($records);

        // Рендерим главную страницу
        $twigRenderService->show('main_page', [
            'records' => $records
        ]);
    }
}
