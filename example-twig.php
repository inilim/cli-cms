<?php

use Inilim\Tool\VD;

// Пример использования Twig в CLI-CMS

require_once __DIR__ . '/boot.php';


// Получаем экземпляр Twig Environment из DI-контейнера
$twig = \DI(\App\Service\TwigRenderService::class);

// Рендерим шаблон с данными
$data = [
    'title' => 'CLI-CMS Twig Example',
    'message' => 'This is a sample template rendered via CLI!',
    'items' => ['Item 1', 'Item 2', 'Item 3']
];

try {
    $rendered = $twig->render('test.html', $data);
    echo $rendered;
} catch (Exception $e) {
    echo "Error rendering template: " . $e->getMessage();
}
