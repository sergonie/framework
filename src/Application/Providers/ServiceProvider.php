<?php declare(strict_types=1);

namespace Sergonie\Application\Providers;

use Sergonie\Container\ServiceLocator;
use Psr\Container\ContainerInterface;

/**
 * Can be implemented by module to register additional services.
 *
 * @package Sergonie\Application\Providers
 */
interface ServiceProvider
{
    /**
     * @param ServiceLocator|ContainerInterface $container
     */
    public function provideServices(ContainerInterface $container): void;
}
