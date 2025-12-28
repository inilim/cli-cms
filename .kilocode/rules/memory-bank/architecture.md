# CLI-CMS Architecture

## System Architecture
CLI-CMS использует архитектурный подход, близкий к MVC паттерну, с использованием DI-контейнера для управления зависимостями. Система работает исключительно через командную строку (CLI) без веб-сервера. В системе реализована поддержка структурированного контента в формате Editor.js с обработкой различных типов блоков (заголовки, параграфы, списки, цитаты, код и др.) через сервис `BlockProcessingService` и шаблоны Twig.

## Entry Point
- `index.php` - основная точка входа, вызывает `boot.php`
- `boot.php` - загрузчик приложения, настраивает автозагрузку, DI-контейнер, обработчики ошибок

## Core Components

### Dependency Injection Container
- Используется кастомный DI-контейнер из пакета `inilim/di`
- Регистрация зависимостей происходит в `src/Bind/Main.php`
- Конфигурация передается через тег 'config'
- Для получения экземпляров классов используются глобальные функции `DI()` и `DITag()`

### Error Handling
- `src/ErrorHandler.php` - централизованный обработчик ошибок и исключений
- Использует `set_error_handler` и `set_exception_handler` для перехвата ошибок
- Обрабатывает различные уровни ошибок (E_DEPRECATED, E_NOTICE и др.)
- Все ошибки логируются через `Logger`

### Logging System
- `src/Logger.php` - простая система логирования в JSON-формате
- Логи записываются в файл `files/logs.log`
- Поддерживает трассировку вызовов для отладки

### Template System
- `src/Service/TwigRenderService.php` - сервис для рендеринга Twig-шаблонов
- Шаблоны хранятся в директории `files/templates` с поддержкой кэширования
- Используется Twig 3.x в качестве шаблонизатора с настройками: debug=true, auto_reload=true, strict_variables=true
- Поддерживает вложенные шаблоны для различных типов блоков контента

### Entity Layer
- `src/Entity/RecordEntity.php` - сущность для представления записи из репозитория RecordRepository
- Содержит поля: id, categoryId, body, shortBody, seoTitle, createdAtMs с использованием модификатора `protected(set)`
- `src/Entity/CategoryEntity.php` - сущность для представления категории из репозитория CategoryRepository
- `src/Entity/RecordWithCategoryEntity.php` - сущность для представления записи вместе с её категорией

### Repository Pattern
- `src/Repository/RepositoryAbstract.php` - абстрактный класс для репозиториев
- `src/Repository/RecordRepository.php` - репозиторий для работы с записями
- `src/Repository/CategoryRepository.php` - репозиторий для работы с категориями
- Использует кастомную обертку `IPDOSQLite` из пакета `inilim/ipdo` с поддержкой шаблонов SQL-запросов
- Включает специфичные функции SQLite (CRC_32, UNIX_MS)

### Service Layer
- `src/Service/BlockProcessingService.php` - сервис для обработки блоков контента из JSON-тела записи (Editor.js формат)
- `src/Service/TwigRenderService.php` - сервис для рендеринга Twig-шаблонов
- `src/Service/FillFnForDbService.php` - сервис для добавления пользовательских функций в базу данных (CRC_32, UNIX_MS)

### Controller Layer
- `src/Controller/ControllerAbstract.php` - абстрактный класс для контроллеров
- `src/Controller/MainPageController.php` - контроллер для обработки главной страницы
- `src/Controller/RecordPageController.php` - контроллер для отображения отдельной страницы записи
- Контроллеры получают зависимости через DI-контейнер с использованием глобальных функций `DI()`

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
- Используется SQLite через кастомную обертку `IPDOSQLite` из пакета `inilim/ipdo`
- База данных находится в `files/db/base.sqlite`
- Поддержка специфичных функций SQLite (CRC_32, UNIX_MS) через `FillFnForDbService`
- Структура базы данных определена в файлах `files/sql/table_categories.sql` и `files/sql/table_records.sql`

## Template Layer
- Используется Twig 3.x в качестве шаблонизатора
- Шаблоны хранятся в директории `files/templates` с поддержкой кэширования в `files/cache/twig`
- Поддержка вложенных шаблонов для различных типов блоков контента
- Структура шаблонов: `files/templates/main_page.twig` - основной шаблон, `files/templates/record_page.twig` - шаблон для отдельной страницы записи с улучшенным оформлением, `files/templates/blocks/` - шаблоны для различных типов блоков, `files/templates/styles/` - CSS-стили
- Поддержка динамического включения шаблонов на основе типа блока: `{% include block.template ~ '.twig' with block.data %}`

## Content Processing
- Поддержка структурированного контента в формате Editor.js JSON
- Обработка различных типов блоков: заголовки, параграфы, цитаты, код, списки и др. через `BlockProcessingService`
- Конвертация JSON-блока в соответствующий Twig-шаблон для отображения
- Пример структуры: `files/other/examples/record_body.json`

## Configuration
- `config.php.example` - пример конфигурационного файла
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
- Использование модификатора `protected(set)` для свойств сущностей
- Поддержка пользовательских функций в базе данных (CRC_32, UNIX_MS)

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
- Пример использования: `example-main-page.php` → `MainPageController` → отображение записей с обработанными блоками
- Пример шаблонизации: `example-twig.php` → `TwigRenderService` → рендеринг шаблонов в CLI