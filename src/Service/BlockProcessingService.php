<?php

namespace App\Service;

/**
 * Сервис для обработки блоков контента из JSON-тела записи
 * 
 * @psalm-type Block = array{id:string,type:string,data:array<string, mixed>}
 * @psalm-type ProcessedBlock = array{id:string,type:string,data:array<string,mixed>,template:string}
 */
final class BlockProcessingService
{
    /**
     * Обрабатывает блоки контента и возвращает их в готовом для шаблона виде
     *
     * @param Block[] $blocks Массив блоков из JSON-тела записи
     * @return ProcessedBlock[] Обработанные блоки с указанием шаблонов
     */
    function processBlocks(array $blocks): array
    {
        $processedBlocks = [];
        foreach ($blocks as $block) {
            $processedBlock = [
                'id'   => $block['id'] ?? '',
                'type' => $block['type'] ?? '',
                'data' => $block['data'] ?? []
            ];

            // В зависимости от типа блока, определяем соответствующий шаблон
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

    /**
     * Обрабатывает тело записи (в формате JSON), извлекая и обрабатывая блоки
     *
     * @param string $body JSON-строка с содержимым записи
     * @return ProcessedBlock[] Обработанные блоки или пустой массив в случае ошибки
     */
    function processBody(string $body): array
    {
        $decodedBody = \json_decode($body, true);

        if (!$decodedBody || !isset($decodedBody['blocks'])) {
            return [];
        }

        return $this->processBlocks($decodedBody['blocks']);
    }
}
