<?php

declare(strict_types=1);

namespace App\Bind;

use Inilim\DI\Bind;

/**
 * Bind для контроллера MainPage
 */
final class MainPage
{
    function __invoke(): void
    {
        $bind = Bind::self();

        $bind->singletonList([
            \App\Service\RecordBlockProcessingService::class,
        ]);
    }
}
