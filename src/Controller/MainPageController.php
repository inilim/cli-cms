<?php

namespace App\Controller;

use Inilim\Tool\VD;
use Inilim\Tool\File;
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
        $recordRepository = \DI(RecordRepository::class);
        $twigRenderService = \DI(TwigRenderService::class);

        // Получаем записи для главной страницы
        $records = $recordRepository->getForMainPage();

        // Обрабатываем каждую запись, преобразуя JSON-тело в структурированные данные
        $processedRecords = [];
        foreach ($records as $record) {
            $body = json_decode($record['body'], true);
            if ($body && isset($body['blocks'])) {
                $processedBlocks = $this->processBlocks($body['blocks']);
                $processedRecords[] = [
                    'id'            => $record['id'],
                    'category_id'   => $record['category_id'],
                    'blocks'        => $processedBlocks,
                    'created_at_ms' => $record['created_at_ms']
                ];
            }
        }

        // Рендерим главную страницу
        $twigRenderService->show('main_page', [
            'records' => $processedRecords
        ]);
    }

    /**
     * Обрабатывает блоки контента и возвращает их в готовом для шаблона виде
     *
     * @param array $blocks
     * @return array
     */
    protected function processBlocks(array $blocks): array
    {
        $processedBlocks = [];
        foreach ($blocks as $block) {
            $processedBlock = [
                'id'   => $block['id'] ?? '',
                'type' => $block['type'] ?? '',
                'data' => $block['data'] ?? []
            ];

            // В зависимости от типа блока, можем добавить дополнительные обработки
            switch ($block['type']) {
                case 'header':
                    $processedBlock['template'] = 'blocks/header_block';
                    break;
                case 'paragraph':
                    $processedBlock['template'] = 'blocks/paragraph_block';
                    break;
                case 'quote':
                    $processedBlock['template'] = 'blocks/quote_block';
                    break;
                case 'raw':
                    $processedBlock['template'] = 'blocks/raw_block';
                    break;
                case 'code':
                    $processedBlock['template'] = 'blocks/code_block';
                    break;
                case 'List': // Обратите внимание на заглавную L - это соответствует формату в примере
                    $processedBlock['template'] = 'blocks/list_block';
                    break;
                default:
                    $processedBlock['template'] = 'blocks/default_block';
                    break;
            }

            $processedBlocks[] = $processedBlock;
        }

        return $processedBlocks;
    }
}
