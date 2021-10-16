<?php

declare(strict_types=1);

namespace Rhapsony\ViewModelHydrator\Tests;

use Rhapsony\ViewModelHydrator\Tests\Fixtures\TestViewModel;
use Rhapsony\ViewModelHydrator\ViewModelHydrator;
use Rhapsony\ViewModelHydrator\ViewModelHydratorInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class AbstractTestCase extends KernelTestCase
{
    public const DATETIME_INITIAL = '1990-01-01 00:00:00';
    public const DATETIME_HYDRATED = '1990-12-31 23:59:59';

    protected ViewModelHydrator $hydrator;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->hydrator = static::getContainer()->get(ViewModelHydratorInterface::class);
    }

    private function assertDateTime(string $expected, TestViewModel $viewModel): void
    {
        $actual = $viewModel->getDateTime()->format('Y-m-d H:i:s');
        $this->assertEquals($expected, $actual);
    }

    protected function assertInitialDateTime(TestViewModel $viewModel): void
    {
        $this->assertDateTime(self::DATETIME_INITIAL, $viewModel);
    }

    protected function assertHydratedDateTime(TestViewModel $viewModel): void
    {
        $this->assertDateTime(self::DATETIME_HYDRATED, $viewModel);
    }
}