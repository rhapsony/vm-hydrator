<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Rhapsony\ViewModelHydrator\ViewModelHydrator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('Rhapsony\\ViewModelHydrator\\', __DIR__ . '/../src')
        ->exclude([
            __DIR__ . '/../src/DependencyInjection/',
            __DIR__ . '/../src/Exception/',
            __DIR__ . '/../src/ViewModelHydratorBundle.php'
        ]);

    // Default directory containing SQL files
    $services->get(ViewModelHydrator::class)
        ->arg('$sqlDirectory', param('kernel.project_dir') . '/config/vm-sql');
};