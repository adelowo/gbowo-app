<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;

abstract class AbstractRepository
{

    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
}
