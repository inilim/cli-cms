<?php

declare(strict_types=1);

namespace App;

use Inilim\Tool\File;
use Inilim\Tool\Json;

class Logger
{
    function __construct(
        protected string $pathToFile
    ) {}

    function __invoke(mixed ...$values): void
    {
        $values = ['values' => $values];
        $values['trace'] = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        $json = Json::tryEncode($values);
        if ($json === null) {
            $json = Json::tryEncode(\print_r($values, true), default: '[]');
        }
        File::put($this->pathToFile, $json . PHP_EOL, \FILE_APPEND);
    }
}
