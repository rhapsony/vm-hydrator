<?php

declare(strict_types=1);

namespace Rhapsony\ViewModelHydrator;

use Rhapsony\ViewModelHydrator\DependencyInjection\Extension\ViewModelHydratorExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class ViewModelHydratorBundle extends Bundle
{
    protected function createContainerExtension()
    {
        return new ViewModelHydratorExtension();
    }
}