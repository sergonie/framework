<?php declare(strict_types=1);

namespace Sergonie\Tests\Fixtures\Modules;

use Sergonie\Application\Providers\ServiceProvider;
use Sergonie\Container\ServiceLocator;
use Psr\Container\ContainerInterface;

class ExampleModuleB implements ServiceProvider
{
    /**
     * @param ServiceLocator $container
     */
    public function provideServices(ContainerInterface $container): void
    {

    }
}
