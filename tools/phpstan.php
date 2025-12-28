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
$process = new Process(['php84', $phpstan, 'analyze', '--error-format=json']);
$process->run();
$output = $process->getOutput();
$output = Str::trim($output);
$output = \json_decode($output, true);
// VD::de($output);
$errorSkip = 0;
foreach ($output['files'] as $ptf => $errors) {
    $errors = $errors['messages'];
    foreach ($errors as $idx => $err) {
        if (\str_contains($err['message'], 'undefined static method Inilim\\Tool\\')) {
            unset($errors[$idx]);
            $errorSkip++;
            continue;
        }
    }

    if (!$errors) {
        continue;
    }

    echo \sprintf('%s -------------------------------------------------------- 
  %s
 -------------------------------------------------------- %s', PHP_EOL, $ptf, PHP_EOL);

    foreach ($errors as $err) {

        echo \sprintf('  Line: %s', $err['line']) . PHP_EOL;
        echo \sprintf('  Message: %s', $err['message']) . PHP_EOL;
        if (isset($err['tip'])) {
            echo \sprintf('  Tip: "%s"', $err['tip']) . PHP_EOL;
        }
        echo '  ' . $err['identifier'] . PHP_EOL;
        echo '  --------------------------------------------------------';
        echo PHP_EOL;
    }

    // VD::de();
}

$countErrors = $output['totals']['file_errors'] - $errorSkip;
echo PHP_EOL;
echo \sprintf(' [ERROR] Found %s errors ', $countErrors);
