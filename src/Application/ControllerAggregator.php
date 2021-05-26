<?php declare(strict_types=1);

namespace Sergonie\Application;

interface ControllerAggregator
{
    /**
     * @param Controller|callable|string $controller
     */
    public function register($controller): void;
}
