<?php

declare(strict_types=1);

namespace Rhapsony\ViewModelHydrator\Tests\HttpKernel;

use Rhapsony\ViewModelHydrator\Tests\Fixtures\MockQueryExecutor;
use Rhapsony\ViewModelHydrator\ViewModelHydrator;
use Rhapsony\ViewModelHydrator\ViewModelHydratorBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();
        yield new ViewModelHydratorBundle();
    }

    public function configureContainer(ContainerConfigurator $container): void
    {
        $container->import(__DIR__ . '/../../config/config.php');
        $container->import(__DIR__ . '/../Fixtures/config.yaml');
    }

    protected function buildContainer()
    {
        $container = parent::buildContainer();
        $container->register(MockQueryExecutor::class);
        $container->getDefinition(ViewModelHydrator::class)
            ->setPublic(true)
            ->setArgument('$connection', new Reference(MockQueryExecutor::class));
        return $container;
    }
}