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

### Repository Pattern
- `src/Repository/RepositoryAbstract.php` - абстрактный класс для репозиториев
- `src/Repository/RecordRepository.php` - пример конкретной реализации
- Использует SQLite через пакет `inilim/ipdo`

## Source Code Paths
- `src/` - основной каталог с исходным кодом
- `src/Bind/` - компоненты привязки (связывания зависимостей)
- `src/Controller/` - контроллеры приложения (пустая директория на данный момент)
- `src/Enum/` - перечисления (пустая директория на данный момент)
- `src/Exception/` - исключения (пустая директория на данный момент)
- `src/Repository/` - репозитории для работы с данными

## Database Layer
- Используется SQLite через кастомную обертку `IPDOSQLite`
- База данных находится в `files/db/records.sqlite`
- Поддержка специфичных функций SQLite (CRC_32, UNIX_MS)

## Configuration
- `config.php` - основной конфигурационный файл
- Путь к лог-файлу: `/files/logs.log`
- Путь к директории базы данных: `/files/db`

## Key Technical Decisions
- Минималистичный подход без использования полноценных фреймворков
- Работа исключительно через CLI без веб-интерфейса
- Использование пространств имен для организации кода
- Автозагрузка классов через Composer PSR-4

## Component Relationships
```
index.php → boot.php → DI Container → Bindings → Controllers/Repositories
                                    ↓
                              ErrorHandler → Logger
                                    ↓
                              Repositories → SQLite DB
```

## Critical Implementation Paths
- Запуск приложения: `index.php` → `boot.php` → `Main` binder → `ErrorHandler`
- Обработка ошибок: PHP error handlers → `ErrorHandler` → `Logger`
- Работа с данными: `Repository` → `IPDOSQLite` → SQLite database