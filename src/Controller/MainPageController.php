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
        $records = $recordRepository->getForMainPageWithCategory();

        // Обрабатываем каждую запись, преобразуя JSON-тело в структурированные данные
        foreach ($records as $record) {
            $record->setProp(
                'blocks',
                $blockProcessingService->processBody($record->short_body)
            );
            $record->removeProps(['short_body', 'body']);
        }

        // Рендерим главную страницу
        $twigRenderService->show('main_page', [
            'records' => $records
        ]);
    }
}
