<?php

declare(strict_types=1);

namespace Rhapsony\ViewModelHydrator\Tests;

use DateTime;
use Rhapsony\ViewModelHydrator\Tests\Fixtures\TestNestedViewModel;
use Rhapsony\ViewModelHydrator\Tests\Fixtures\TestViewModel;

class DataHydrationTest extends AbstractTestCase
{
    public function testHydrateCompleteViewModelInOneStep(): void
    {
        $expected = (new TestViewModel())
            ->setString('string')
            ->setBool(true)
            ->setInt(1)
            ->setFloat(1.5)
            ->setDateTime(new DateTime(self::DATETIME_HYDRATED));
        $expected->getNestedViewModel()
            ->setString('nested-string')
            ->setBool(true)
            ->setInt(2)
            ->setFloat(4.5);
        $expected->setSimpleArray([
            (new TestNestedViewModel())
                ->setString('string-1')
                ->setBool(true)
                ->setInt(1)
                ->setFloat(1.1),
            (new TestNestedViewModel())
                ->setString('string-2')
                ->setBool(true)
                ->setInt(2)
                ->setFloat(2.2),
            (new TestNestedViewModel())
                ->setString('string-3')
                ->setBool(true)
                ->setInt(3)
                ->setFloat(3.3)
        ]);

        /** @var TestViewModel $actual */
        $actual = $this->hydrator
            ->start(TestViewModel::class)
            ->data([
                'string' => 'string',
                'bool' => true,
                'int' => 1,
                'float' => 1.5,
                'dateTime' => self::DATETIME_HYDRATED,
                'nestedViewModel' => [
                    'string' => 'nested-string',
                    'bool' => true,
                    'int' => 2,
                    'float' => 4.5
                ],
                'simpleArray' => [
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
                ]
            ])
            ->finish();

        $this->assertEquals($expected, $actual);
        $this->assertHydratedDateTime($actual);
    }

    public function testHydrateCompleteViewModelInTwoSteps(): void
    {
        $expected = (new TestViewModel())
            ->setString('string')
            ->setBool(true)
            ->setInt(1)
            ->setFloat(1.5)
            ->setDateTime(new DateTime(self::DATETIME_HYDRATED));
        $expected->getNestedViewModel()
            ->setString('nested-string')
            ->setBool(true)
            ->setInt(2)
            ->setFloat(4.5);
        $expected->setSimpleArray([
            (new TestNestedViewModel())
                ->setString('string-1')
                ->setBool(true)
                ->setInt(1)
                ->setFloat(1.1),
            (new TestNestedViewModel())
                ->setString('string-2')
                ->setBool(true)
                ->setInt(2)
                ->setFloat(2.2),
            (new TestNestedViewModel())
                ->setString('string-3')
                ->setBool(true)
                ->setInt(3)
                ->setFloat(3.3)
        ]);

        $actual = $this->hydrator
            ->start(TestViewModel::class)
            ->data([
                'string' => 'string',
                'bool' => true,
                'int' => 1,
                'float' => 1.5,
                'dateTime' => self::DATETIME_HYDRATED
            ])
            ->next()
            ->data([
                'nestedViewModel' => [
                    'string' => 'nested-string',
                    'bool' => true,
                    'int' => 2,
                    'float' => 4.5
                ],
                'simpleArray' => [
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
                ]
            ])
            ->finish();

        $this->assertEquals($expected, $actual);
        $this->assertHydratedDateTime($actual);
    }

    public function testHydrateCompleteViewModelInThreeSteps(): void
    {
        $expected = (new TestViewModel())
            ->setString('string')
            ->setBool(true)
            ->setInt(1)
            ->setFloat(1.5)
            ->setDateTime(new DateTime(self::DATETIME_HYDRATED));
        $expected->getNestedViewModel()
            ->setString('nested-string')
            ->setBool(true)
            ->setInt(2)
            ->setFloat(4.5);
        $expected->setSimpleArray([
            (new TestNestedViewModel())
                ->setString('string-1')
                ->setBool(true)
                ->setInt(1)
                ->setFloat(1.1),
            (new TestNestedViewModel())
                ->setString('string-2')
                ->setBool(true)
                ->setInt(2)
                ->setFloat(2.2),
            (new TestNestedViewModel())
                ->setString('string-3')
                ->setBool(true)
                ->setInt(3)
                ->setFloat(3.3)
        ]);

        $actual = $this->hydrator
            ->start(TestViewModel::class)
            ->data([
                'string' => 'string',
                'bool' => true,
                'int' => 1,
                'float' => 1.5,
                'dateTime' => self::DATETIME_HYDRATED
            ])
            ->next()
            ->data([
                'nestedViewModel' => [
                    'string' => 'nested-string',
                    'bool' => true,
                    'int' => 2,
                    'float' => 4.5
                ]
            ])
            ->next()
            ->propertyPath('simpleArray')
            ->data([
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
            ])
            ->finish();

        $this->assertEquals($expected, $actual);
        $this->assertHydratedDateTime($actual);
    }

    public function testHydrateRootProperties(): void
    {
        $expected = (new TestViewModel())
            ->setString('string')
            ->setBool(true)
            ->setInt(1)
            ->setFloat(1.5)
            ->setDateTime(new DateTime(self::DATETIME_HYDRATED));

        $actual = $this->hydrator
            ->start(TestViewModel::class)
            ->data([
                'string' => 'string',
                'bool' => true,
                'int' => 1,
                'float' => 1.5,
                'dateTime' => self::DATETIME_HYDRATED
            ])
            ->finish();

        $this->assertEquals($expected, $actual);
        $this->assertHydratedDateTime($actual);
    }

    public function testHydratePropertyPath(): void
    {
        $expected = new TestViewModel();
        $expected->getNestedViewModel()
            ->setString('nested-string')
            ->setBool(true)
            ->setInt(2)
            ->setFloat(4.5);

        $actual = $this->hydrator
            ->start(TestViewModel::class)
            ->propertyPath('nestedViewModel')
            ->data([
                'string' => 'nested-string',
                'bool' => true,
                'int' => 2,
                'float' => 4.5
            ])
            ->finish();

        $this->assertEquals($expected, $actual);
        $this->assertInitialDateTime($actual);
    }

    public function testSetPropertyPathDirectly(): void
    {
        $nested = (new TestNestedViewModel())
            ->setString('nested-string')
            ->setBool(true)
            ->setInt(2)
            ->setFloat(4.5);

        $expected = (new TestViewModel())
            ->setString('string')
            ->setBool(true)
            ->setInt(1)
            ->setFloat(1.5)
            ->setDateTime(new DateTime(self::DATETIME_HYDRATED));
        $expected->getNestedViewModel()
            ->setString('nested-string')
            ->setBool(true)
            ->setInt(2)
            ->setFloat(4.5);

        $actual = $this->hydrator
            ->start(TestViewModel::class)
            ->propertyPath('nestedViewModel')
            ->set('nestedViewModel', $nested)
            ->next()
            ->data([
                'string' => 'string',
                'bool' => true,
                'int' => 1,
                'float' => 1.5,
                'dateTime' => self::DATETIME_HYDRATED
            ])
            ->finish();

        $this->assertEquals($expected, $actual);
    }
}