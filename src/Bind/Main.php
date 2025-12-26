<?php

namespace App\Bind;

use App\Logger;
use Inilim\DI\Bind;
use Inilim\Env\Env;
use Inilim\IPDO\IPDOSQLite;

/**
 * Bind основных классов
 */
final class Main
{
    function __invoke(): void
    {
        $bind = Bind::self();

        $bind->singletonTag('db', static function ($di) {
            $config = $di->DITag('config');
            /** @var Env $config */
            $connect = new IPDOSQLite($config->getStr('db_dir') . '/base.sqlite');
            (new \App\Service\FillFnForDbService)->__invoke($connect);
            return $connect;
        });

        $bind->singleton(Logger::class, static function ($di) {
            $config = $di->DITag('config');
            /** @var Env $config */
            return new Logger($config->getStr('log_file'));
        })
            ->singletonList([
                \App\Service\TwigRenderService::class,
            ]);
    }
}
