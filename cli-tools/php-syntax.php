<?php

use Inilim\Tool\FS;
use Inilim\Tool\VD;
use Inilim\Tool\Arr;
use Inilim\Tool\Str;
use Inilim\Tool\Path;
use Symfony\Component\Process\Process;

require_once __DIR__ . '/../vendor/autoload.php';

$files = $argv;
unset($files[0]);
// $files = \array_values($files);

if (!$files) {
    VD::de('The files were not transferred for verification.');
}

$root = Path::realPath(__DIR__ . '/../');
$root = Path::normalize($root);

$notFound = [];
foreach ($files as $idx => $file) {
    $absolute = Path::normalize($root . '/' . $file);
    $relative = \trim(Str::after($absolute, $root), '/');
    if (!FS::isFile($absolute)) {
        $notFound[] = [
            'absolute' => $absolute,
            'relative' => $relative,
        ];
        unset($files[$idx]);
    } else {
        $files[$idx] = [
            'absolute' => $absolute,
            'relative' => $relative,
        ];
    }
}

foreach ($files as $idx => $file) {
    $process = new Process([
        'php84',
        '-l',
        $file['absolute']
    ]);
    $process->run();
    $files[$idx]['output'] = $process->getOutput();
}

// VD::dd($files);

foreach ($files as $idx => $file) {
    echo '----------------------------------------' . PHP_EOL;
    echo \sprintf('"%s"', $file['relative']) . PHP_EOL;
    echo $file['output'] . PHP_EOL;
    echo '----------------------------------------' . PHP_EOL;
}

echo PHP_EOL;

foreach ($notFound as $file) {
    echo '----------------------------------------' . PHP_EOL;
    echo \sprintf('"%s"', $file['relative']) . PHP_EOL;
    echo \sprintf('File not found "%s".', $file['relative']) . PHP_EOL;
    echo '----------------------------------------' . PHP_EOL;
}
