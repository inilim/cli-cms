<?php

// файл для проверок процедур

use Inilim\Tool\VD;
use Inilim\Tool\Exp;
use App\Repository\RecordRepository;

require_once __DIR__ . '/boot.php';


$_SERVER;

$h = Exp::normalizeHeaders();
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
