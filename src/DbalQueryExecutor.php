<?php

declare(strict_types=1);

namespace Rhapsony\ViewModelHydrator;

use Doctrine\DBAL\Connection;
use Rhapsony\ViewModelHydrator\Exception\UnsupportedFetchModeException;

class DbalQueryExecutor implements QueryExecutorInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @inheritDoc
     */
    public function execute(string $path, array $params, array $types, string $fetchMode)
    {
        $sql = file_get_contents($path);

        $result = $this->connection->executeQuery($sql, $params, $types);
        switch ($fetchMode) {
            case ViewModelHydratorInterface::FETCH_COLLECTION:
                $ret = $result->fetchAllAssociative();
                if (false === $ret) return [];
                return $ret;
            case ViewModelHydratorInterface::FETCH_COLUMN:
                $ret = $result->fetchOne();
                if (false === $ret) return null;
                return $ret;
            case ViewModelHydratorInterface::FETCH_ROW:
                $ret = $result->fetchAssociative();
                if (false === $ret) return [];
                return $ret;
            default:
                throw new UnsupportedFetchModeException();
        }
    }
}