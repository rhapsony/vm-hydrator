<?php

declare(strict_types=1);

namespace Rhapsony\ViewModelHydrator\Tests;

use Rhapsony\ViewModelHydrator\Tests\Fixtures\TestNestedViewModel;
use Rhapsony\ViewModelHydrator\Tests\Fixtures\TestViewModel;

class CombinedHydrationTest extends AbstractTestCase
{
    private function getExceptedViewModel(): TestViewModel
    {
        $expected = new TestViewModel();
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
        return $expected;
    }

    public function testHydrateFirstTwoPropertiesFromDataAndLastTwoPropertiesFromQueryAndArrayFromData(): void
    {
        $expected = $this->getExceptedViewModel();
        $actual = $this->hydrator
            ->start(TestViewModel::class)
            ->propertyPath('nestedViewModel')
            ->query('nested-properties-part-1')
            ->data([
                'int' => 2,
                'float' => 4.5
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
    }

    public function testHydrateFirstTwoPropertiesFromDataAndLastTwoPropertiesFromQueryAndArrayFromQuery(): void
    {
        $expected = $this->getExceptedViewModel();
        $actual = $this->hydrator
            ->start(TestViewModel::class)
            ->propertyPath('nestedViewModel')
            ->query('nested-properties-part-1')
            ->data([
                'int' => 2,
                'float' => 4.5
            ])
            ->next()
            ->propertyPath('simpleArray')
            ->query('simple-array')
            ->finish();

        $this->assertEquals($expected, $actual);
    }

    public function testHydrateRootPropertiesFromQueryAndNestedPropertiesFromDataAndArrayFromData(): void
    {
        $expected = $this->getExceptedViewModel();
        $actual = $this->hydrator
            ->start(TestViewModel::class)
            ->propertyPath('nestedViewModel')
            ->query('nested-properties-part-2')
            ->data([
                'string' => 'nested-string',
                'bool' => true
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
    }

    public function testHydrateRootPropertiesFromQueryAndNestedPropertiesFromDataAndArrayFromQuery(): void
    {
        $expected = $this->getExceptedViewModel();
        $actual = $this->hydrator
            ->start(TestViewModel::class)
            ->propertyPath('nestedViewModel')
            ->query('nested-properties-part-2')
            ->data([
                'string' => 'nested-string',
                'bool' => true
            ])
            ->next()
            ->propertyPath('simpleArray')
            ->query('simple-array')
            ->finish();

        $this->assertEquals($expected, $actual);
    }
}