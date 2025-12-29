<?php

declare(strict_types=1);

use App\Bind\Main;
use Inilim\DI\Bind;
use Inilim\Env\Env;
use App\ErrorHandler;
use Inilim\Tool\Path;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/inilim/tools/src/all.php';

\define('ROOT', Path::normalize(__DIR__));

\date_default_timezone_set('UTC');
\ini_set('display_errors', 1);
\error_reporting(\E_ALL);
// \ini_set('memory_limit', '5mb');
// \set_time_limit($time_limit);
// \ini_set('max_execution_time', $time_limit);

(new Main)->__invoke();
(new ErrorHandler)->register();
