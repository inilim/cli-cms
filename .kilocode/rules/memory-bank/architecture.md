# CLI-CMS Architecture

## System Architecture
CLI-CMS использует архитектурный подход, близкий к MVC паттерну, с использованием DI-контейнера для управления зависимостями. Система работает исключительно через командную строку (CLI) без веб-сервера.

## Entry Point
- `index.php` - основная точка входа, вызывает `boot.php`
- `boot.php` - загрузчик приложения, настраивает автозагрузку, DI-контейнер, обработчики ошибок

## Core Components

### Dependency Injection Container
- Используется кастомный DI-контейнер из пакета `inilim/di`
- Регистрация зависимостей происходит в `src/Bind/Main.php`
- Конфигурация передается через тег 'config'

### Error Handling
- `src/ErrorHandler.php` - централизованный обработчик ошибок и исключений
- Использует `set_error_handler` и `set_exception_handler` для перехвата ошибок
- Все ошибки логируются через `Logger`

### Logging System
- `src/Logger.php` - простая система логирования в JSON-формате
- Логи записываются в файл `files/logs.log`

### Template System
- `src/Service/TwigRenderService.php` - сервис для рендеринга Twig-шаблонов
- Шаблоны хранятся в директории `files/templates`
- Используется Twig 3.x в качестве шаблонизатора

### Entity Layer
- `src/Entity/RecordEntity.php` - сущность для представления записи из репозитория RecordRepository
- Содержит поля: id, categoryId, body, createdAtMs
- `src/Entity/CategoryEntity.php` - сущность для представления категории из репозитория CategoryRepository
- `src/Entity/RecordWithCategoryEntity.php` - сущность для представления записи вместе с её категорией

### Repository Pattern
- `src/Repository/RepositoryAbstract.php` - абстрактный класс для репозиториев
- `src/Repository/RecordRepository.php` - пример конкретной реализации
- `src/Repository/CategoryRepository.php` - репозиторий для работы с категориями
- Использует SQLite через пакет `inilim/ipdo`

### Service Layer
- `src/Service/BlockProcessingService.php` - сервис для обработки блоков контента из JSON-тела записи
- `src/Service/TwigRenderService.php` - сервис для рендеринга Twig-шаблонов
- `src/Service/FillFnForDbService.php` - сервис для добавления пользовательских функций в базу данных

### Controller Layer
- `src/Controller/ControllerAbstract.php` - абстрактный класс для контроллеров
- `src/Controller/MainPageController.php` - контроллер для обработки главной страницы
- Контроллеры получают зависимости через DI-контейнер

### Route System
- `src/Route.php` - класс для обработки маршрутов (заглушка)

## Source Code Paths
- `src/` - основной каталог с исходным кодом
- `src/Bind/` - компоненты привязки (связывания зависимостей)
- `src/Controller/` - контроллеры приложения
- `src/Entity/` - сущности приложения
- `src/Enum/` - перечисления (пустая директория на данный момент)
- `src/Exception/` - исключения (пустая директория на данный момент)
- `src/Repository/` - репозитории для работы с данными
- `src/Service/` - сервисы приложения
- `src/Service/TwigRenderService.php` - сервис для рендеринга Twig-шаблонов
- `src/Service/BlockProcessingService.php` - сервис для обработки блоков контента

## Database Layer
- Используется SQLite через кастомную обертку `IPDOSQLite`
- База данных находится в `files/db/base.sqlite`
- Поддержка специфичных функций SQLite (CRC_32, UNIX_MS)

## Template Layer
- Используется Twig 3.x в качестве шаблонизатора
- Шаблоны хранятся в директории `files/templates`
- Поддержка кэширования шаблонов для производительности
- Интеграция с DI-контейнером для внедрения зависимостей
- Поддержка вложенных шаблонов для различных типов блоков контента
- Структура шаблонов: `files/templates/main_page.twig` - основной шаблон, `files/templates/blocks/` - шаблоны для различных типов блоков, `files/templates/styles/` - CSS-стили

## Content Processing
- Поддержка структурированного контента в формате JSON
- Обработка различных типов блоков: заголовки, параграфы, цитаты, код, списки и др.
- Конвертация JSON-блока в соответствующий Twig-шаблон для отображения
- Пример структуры: `files/other/examples/record_body.json`

## Configuration
- `config.php` - основной конфигурационный файл
- Путь к лог-файлу: `/files/logs.log`
- Путь к директории базы данных: `/files/db`
- Путь к кэшу Twig: `/files/cache/twig`

## Key Technical Decisions
- Минималистичный подход без использования полноценных фреймворков
- Работа исключительно через CLI без веб-интерфейса
- Использование пространств имен для организации кода
- Автозагрузка классов через Composer PSR-4
- Использование Twig для генерации HTML-контента в CLI-приложении
- Поддержка структурированного контента в формате Editor.js

## Component Relationships
```
index.php → boot.php → DI Container → Bindings → Controllers/Repositories/Twig
                                    ↓
                              ErrorHandler → Logger
                                    ↓
                               Repositories → SQLite DB
```

## Critical Implementation Paths
- Запуск приложения: `index.php` → `boot.php` → `Main` binder → `ErrorHandler`
- Обработка ошибок: PHP error handlers → `ErrorHandler` → `Logger`
- Работа с данными: `Repository` → `IPDOSQLite` → SQLite database
- Рендеринг шаблонов: `TwigRenderService` → `Twig Environment` → шаблоны в `files/templates`
- Обработка контента: `BlockProcessingService` → `MainPageController` → `TwigRenderService` → шаблоны в `files/templates/blocks/`