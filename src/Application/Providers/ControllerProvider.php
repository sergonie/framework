<?php declare(strict_types=1);

namespace Sergonie\Application\Providers;

use Sergonie\Application\ControllerAggregator;

/**
 * Can be implemented by module to register controllers in the application scope.
 *
 * @package Sergonie\Application\Providers
 */
interface ControllerProvider
{
    public function provideControllers(ControllerAggregator $aggregator): void;
}
