# CLI-CMS Technologies

## Core Technologies
- PHP 8.4 - основной язык программирования
- SQLite - СУБД для хранения данных
- Composer - менеджер зависимостей

## External Dependencies
- `inilim/tools` - коллекция вспомогательных утилит
- `inilim/ipdo` - обертка для работы с PDO и SQLite
- `inilim/env` - работа с конфигурациями и переменными окружения
- `inilim/di` - контейнер для внедрения зависимостей
- `twig/twig` - шаблонизатор (возможно для будущего использования)

## Development Environment
- Разработка: Windows OS
- Эксплуатация: Ubuntu OS
- IDE: VSCode
- Система контроля версий: Git

## Architecture Technologies
- PSR-4 автозагрузка классов
- Dependency Injection Container
- MVC-подобная архитектура
- Репозиторный паттерн для работы с данными

## Logging & Error Handling
- Собственная система логирования в JSON-формате
- Централизованный обработчик ошибок
- Поддержка различных уровней ошибок (E_DEPRECATED, E_NOTICE и др.)

## File Structure
- `src/` - исходный код с пространствами имен
- `files/` - директория для хранения данных (база данных, логи)
- `vendor/` - зависимости Composer
- `config.php` - файл конфигурации

## Key Technical Features
- Кроссплатформенная совместимость
- Работа исключительно через CLI
- Поддержка специфичных функций SQLite (CRC_32, UNIX_MS)
- Минималистичный подход без использования тяжелых фреймворков