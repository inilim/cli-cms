<?php

use Inilim\Tool\FS;
use Inilim\Tool\VD;
use Inilim\Tool\Arr;
use Inilim\Tool\Str;
use Inilim\Tool\Path;
use Symfony\Component\Process\Process;

require_once __DIR__ . '/../vendor/autoload.php';

$phpstan = __DIR__ . '/../vendor/bin/phpstan';
$phpstan = Path::realPath($phpstan);
$phpstan = Path::normalize($phpstan);
$process = new Process(['php84', $phpstan, 'analyze']);
$process->run();
$output = $process->getOutput();
$output = Str::unixNewLines($output, PHP_EOL);

$errorSkip = 0;
$output = \explode(PHP_EOL, $output);
foreach ($output as $idx => $line) {
    $line = Str::trim($line);
    if (!Str::isMatch('/^\d+\s/', $line)) {
        // VD::de($line);
        continue;
    }
    if (\str_contains($line, 'undefined static method Inilim\\Tool\\')) {
        // unset($output[$idx]);
        // VD::de($line);
        $errorSkip++;
        continue;
    }
}

if ($errorSkip > 0) {
    // VD::de(Arr::last($output));
}

echo \implode(PHP_EOL, $output);
