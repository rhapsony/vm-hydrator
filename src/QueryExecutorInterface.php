<?php

declare(strict_types=1);

namespace Rhapsony\ViewModelHydrator;

use Rhapsony\ViewModelHydrator\Exception\UnsupportedFetchModeException;

interface QueryExecutorInterface
{
    /**
     * Executes a query from an SQL file and returns the result.
     *
     * @param string $path The path to the SQL file
     * @param array $params Parameters to bind to the query
     * @param array $types The query parameter types
     * @param string $fetchMode See ViewModelHydratorInterface::FETCH_*
     * @return mixed
     * @throws UnsupportedFetchModeException
     */
    public function execute(string $path, array $params, array $types, string $fetchMode);
}