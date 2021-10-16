<?php

declare(strict_types=1);

namespace Rhapsony\ViewModelHydrator\Tests;

use Rhapsony\ViewModelHydrator\ViewModelHydrator;
use Rhapsony\ViewModelHydrator\ViewModelHydratorInterface;

class FrameworkTest extends AbstractTestCase
{
    public function testTheServiceGetsRegistered(): void
    {
        $container = static::getContainer();
        $service = $container->get(ViewModelHydratorInterface::class);
        $this->assertInstanceOf(ViewModelHydratorInterface::class, $service);
        $this->assertInstanceOf(ViewModelHydrator::class, $service);
    }
}