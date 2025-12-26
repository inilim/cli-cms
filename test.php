<?php

// файл для проверок процедур

use Inilim\Tool\VD;
use App\Repository\RecordRepository;

require_once __DIR__ . '/boot.php';


$rep = \DI(RecordRepository::class);

$res = $rep->getForMainPage();

VD::de($res);
