<?php

declare(strict_types=1);

namespace Rhapsony\ViewModelHydrator\Tests\Fixtures;

use Rhapsony\ViewModelHydrator\QueryExecutorInterface;
use Rhapsony\ViewModelHydrator\Tests\AbstractTestCase;

class MockQueryExecutor implements QueryExecutorInterface
{
    /**
     * @inheritDoc
     */
    public function execute(string $path, array $params, array $types, string $fetchMode)
    {
        $query = pathinfo(basename($path), PATHINFO_FILENAME);
        switch ($query) {
            case 'root-properties':
                return [
                    'string' => 'string',
                    'bool' => true,
                    'int' => 1,
                    'float' => 1.5,
                    'dateTime' => AbstractTestCase::DATETIME_HYDRATED
                ];
            case 'nested-properties':
                return [
                    'string' => 'nested-string',
                    'bool' => true,
                    'int' => 2,
                    'float' => 4.5
                ];
            case 'nested-properties-part-1':
                return [
                    'string' => 'nested-string',
                    'bool' => true
                ];
            case 'nested-properties-part-2':
                return [
                    'int' => 2,
                    'float' => 4.5
                ];
            case 'single-property-string':
                return 'string';
            case 'single-property-bool':
                return true;
            case 'single-property-int':
                return 1;
            case 'single-property-float':
                return 1.5;
            case 'single-property-datetime-initial':
                return AbstractTestCase::DATETIME_INITIAL;
            case 'single-property-datetime-hydrated':
                return AbstractTestCase::DATETIME_HYDRATED;
            case 'simple-array':
                return [
                    [
                        'string' => 'string-1',
                        'bool' => true,
                        'int' => 1,
                        'float' => 1.1
                    ],
                    [
                        'string' => 'string-2',
                        'bool' => true,
                        'int' => 2,
                        'float' => 2.2
                    ],
                    [
                        'string' => 'string-3',
                        'bool' => true,
                        'int' => 3,
                        'float' => 3.3
                    ]
                ];
        }
        return [];
    }
}