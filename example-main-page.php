<?php

// Пример запуска контроллера главной страницы
// Этот файл можно использовать для тестирования функционала

require_once __DIR__ . '/boot.php';

// Создаем экземпляр контроллера и вызываем его
$controller = new \App\Controller\MainPageController();
$controller->__invoke();
