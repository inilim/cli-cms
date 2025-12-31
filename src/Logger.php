<?php

declare(strict_types=1);

namespace App;

use Monolog\Logger as Monolog;

final class Logger
{
    protected Monolog $monolog;

    function __construct(
        protected string $pathToFile
    ) {
        $log = new Monolog('');
        $stream = new \Monolog\Handler\StreamHandler($pathToFile);
        $stream->setFormatter(new \Monolog\Formatter\JsonFormatter);
        $log->pushHandler($stream);
        $this->monolog = $log;
    }

    function __invoke(mixed ...$values): void
    {
        $this->monolog->info('mixed', $values);
    }

    function error(mixed ...$values): void
    {
        $this->monolog->error('', $values);
    }
}
