<?php

namespace App\Bind;

use App\Logger;
use Inilim\DI\Bind;
use Inilim\Env\Env;

final class Main
{
    function __invoke(): void
    {
        $bind = Bind::self();
        $config = \DITag('config');
        /** @var Env $config */

        $bind->singleton(Logger::class, static function () use ($config) {
            return new Logger($config->getStr('log_file'));
        })
            ->$bind->singletonList([
                // 
            ]);
    }
}
