<?php

declare(strict_types=1);

namespace Rhapsony\ViewModelHydrator\Tests;

use Rhapsony\ViewModelHydrator\Exception\PropertyPathNotSetException;
use Rhapsony\ViewModelHydrator\Exception\UnsupportedFetchModeException;
use Rhapsony\ViewModelHydrator\Exception\ViewModelNotInitializedException;
use Rhapsony\ViewModelHydrator\Tests\Fixtures\TestViewModel;
use Rhapsony\ViewModelHydrator\ViewModelHydratorInterface;

class ExceptionTest extends AbstractTestCase
{
    public function testExceptionWillBeThrownForInvalidFetchMode(): void
    {
        $this->expectException(UnsupportedFetchModeException::class);

        $this->hydrator
            ->start(TestViewModel::class)
            ->fetchMode('invalid');
    }

    public function testExceptionWillBeThrownIfViewModelIsNotInitializedWhenCallingNext(): void
    {
        $this->expectException(ViewModelNotInitializedException::class);

        $this->hydrator->next();
    }

    public function testExceptionWillBeThrownIfViewModelIsNotInitializedWhenCallingFinish(): void
    {
        $this->expectException(ViewModelNotInitializedException::class);

        $this->hydrator->finish();
    }

    public function testExceptionWillBeThrownIfViewModelIsNotInitializedWhenCallingSet(): void
    {
        $this->expectException(ViewModelNotInitializedException::class);

        $this->hydrator->set('test', 'test');
    }

    public function testExceptionWillBeThrownIfNoPropertyPathIsSetForColumn(): void
    {
        $this->expectException(PropertyPathNotSetException::class);

        $this->hydrator
            ->start(TestViewModel::class)
            ->query('single-property-string')
            ->fetchMode(ViewModelHydratorInterface::FETCH_COLUMN)
            ->finish();
    }

    public function testExceptionWillBeThrownIfNoPropertyPathIsSetForCollection(): void
    {
        $this->expectException(PropertyPathNotSetException::class);

        $this->hydrator
            ->start(TestViewModel::class)
            ->query('collection')
            ->fetchMode(ViewModelHydratorInterface::FETCH_COLLECTION)
            ->finish();
    }
}