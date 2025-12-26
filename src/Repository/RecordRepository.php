<?php

namespace App\Repository;

use Inilim\IPDO\IPDOSQLite;
use App\Repository\RepositoryAbstract;

final class RecordRepository extends RepositoryAbstract
{
    function __construct()
    {
        $config = \DITag('config');
        /** @var Env $config */
        $this->connect = new IPDOSQLite($config->getStr('db_dir') . '/records.sqlite');
        parent::__construct();
    }
}
