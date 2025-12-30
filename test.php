<?php

declare(strict_types=1);

// файл для проверок процедур

use Inilim\Tool\VD;
use Inilim\Tool\Exp;
use App\Entity\RecordEntity;
use App\Repository\RecordRepository;

require_once __DIR__ . '/boot.php';



$a = RecordEntity::fromArray(['id' => 'dawd', 'dawd' => 'awdw']);

VD::de($a);
$_SERVER;

$request = [
    'path'    => '',
    'details' => [
        'id'      => '...', // important
        'headers' => [], // important
        'query'   => [], // important
        'method'  => 'GET', // important
        'host'  => '', // important
        'uri' => '', // ?
        'target' => '', // ?
        'protocol' => '',
        'cookies' => [], // ?
        'files' => [], // ?
    ],
    'body'    => '',
];

// path string
// details array
// body

VD::de($res);
