<?php

use App\Bind\Main;
use Inilim\DI\Bind;
use Inilim\Env\Env;
use App\ErrorHandler;

require_once __DIR__ . '/vendor/autoload.php';

\date_default_timezone_set('UTC');
\ini_set('display_errors', 1);
// \ini_set('memory_limit', '5mb');
\error_reporting(\E_ALL);
// \set_time_limit($time_limit);
// \ini_set('max_execution_time', $time_limit);

(static function () {
    $config = new Env;
    $config->loadFromFile(__DIR__ . '/config.php');
    Bind::self()->singletonTag('config', $config);
    (new Main)->__invoke();
    (new ErrorHandler)->register();
})();
