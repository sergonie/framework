<?php declare(strict_types=1);

namespace Sergonie\Application\Listeners;

use Sergonie\Application\Application;

/**
 * Can be implemented by module to perform clean-up tasks.
 *
 * @package Sergonie\Application\Listeners
 */
interface OnShutDownListener
{
    public function onShutDown(Application $application): void;
}
