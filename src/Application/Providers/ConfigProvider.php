<?php declare(strict_types=1);

namespace Sergonie\Application\Providers;

use Sergonie\Application\Config;

/**
 * Can be implemented by module to provide additional configuration
 * for application.
 *
 * @package Sergonie\Application\Providers
 */
interface ConfigProvider
{
    /**
     * @param Config $config
     */
    public function provideConfig(Config $config): void;
}
