<?php declare(strict_types=1);

namespace Sergonie\Application\Http;

use Psr\Http\Server\MiddlewareInterface;

/**
 * Used by http application.
 *
 * @package Sergonie\Application
 */
interface MiddlewareAggregator
{
    /**
     * @param string|MiddlewareInterface|callable $middleware
     */
    public function use($middleware): void;
}
