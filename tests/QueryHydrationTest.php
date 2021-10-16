<?php

declare(strict_types=1);

namespace Rhapsony\ViewModelHydrator\Tests;

use DateTime;
use Rhapsony\ViewModelHydrator\Tests\Fixtures\TestNestedViewModel;
use Rhapsony\ViewModelHydrator\Tests\Fixtures\TestViewModel;
use Rhapsony\ViewModelHydrator\ViewModelHydratorInterface;

class QueryHydrationTest extends AbstractTestCase
{
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
            ->query('root-properties')
            ->next()
            ->propertyPath('nestedViewModel')
            ->query('nested-properties')
            ->next()
            ->propertyPath('simpleArray')
            ->query('simple-array')
            ->finish();

        $this->assertEquals($expected, $actual);
        $this->assertHydratedDateTime($actual);
    }

    public function testHydrateCompleteViewModelInFourSteps(): void
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
            ->query('root-properties')
            ->next()
            ->propertyPath('nestedViewModel')
            ->query('nested-properties-part-1')
            ->next()
            ->propertyPath('nestedViewModel')
            ->query('nested-properties-part-2')
            ->next()
            ->propertyPath('simpleArray')
            ->query('simple-array')
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
            ->query('root-properties')
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
            ->query('nested-properties')
            ->finish();

        $this->assertEquals($expected, $actual);
        $this->assertInitialDateTime($actual);
    }

    public function testHydrateSingleProperty(): void
    {
        $expected = (new TestViewModel())
            ->setString('string')
            ->setDateTime(new DateTime(self::DATETIME_HYDRATED));
        $actual = $this->hydrator
            ->start(TestViewModel::class)
            ->propertyPath('string')
            ->query('single-property-string')
            ->fetchMode(ViewModelHydratorInterface::FETCH_COLUMN)
            ->next()
            ->propertyPath('dateTime')
            ->query('single-property-datetime-hydrated')
            ->fetchMode(ViewModelHydratorInterface::FETCH_COLUMN)
            ->finish();
        $this->assertEquals($expected, $actual);
        $this->assertHydratedDateTime($actual);

        $expected = (new TestViewModel())->setBool(true);
        $actual = $this->hydrator
            ->start(TestViewModel::class)
            ->propertyPath('bool')
            ->query('single-property-bool')
            ->fetchMode(ViewModelHydratorInterface::FETCH_COLUMN)
            ->next()
            ->propertyPath('dateTime')
            ->query('single-property-datetime-initial')
            ->fetchMode(ViewModelHydratorInterface::FETCH_COLUMN)
            ->finish();
        $this->assertEquals($expected, $actual);
        $this->assertInitialDateTime($actual);

        $expected = (new TestViewModel())
            ->setInt(1)
            ->setDateTime(new DateTime(self::DATETIME_HYDRATED));
        $actual = $this->hydrator
            ->start(TestViewModel::class)
            ->propertyPath('int')
            ->query('single-property-int')
            ->fetchMode(ViewModelHydratorInterface::FETCH_COLUMN)
            ->next()
            ->propertyPath('dateTime')
            ->query('single-property-datetime-hydrated')
            ->fetchMode(ViewModelHydratorInterface::FETCH_COLUMN)
            ->finish();
        $this->assertEquals($expected, $actual);
        $this->assertHydratedDateTime($actual);

        $expected = (new TestViewModel())->setFloat(1.5);
        $actual = $this->hydrator
            ->start(TestViewModel::class)
            ->propertyPath('float')
            ->query('single-property-float')
            ->fetchMode(ViewModelHydratorInterface::FETCH_COLUMN)
            ->next()
            ->propertyPath('dateTime')
            ->query('single-property-datetime-initial')
            ->fetchMode(ViewModelHydratorInterface::FETCH_COLUMN)
            ->finish();
        $this->assertEquals($expected, $actual);
        $this->assertInitialDateTime($actual);
    }
}