<?php declare(strict_types=1);

namespace Sergonie\Application\Listeners;

use Sergonie\Application\Application;

/**
 * Can be implemented by module to perform tasks when application boots-up (modules are loaded but not handled),
 *
 * @package Sergonie\Application\Listeners
 */
interface OnBootListener
{
    public function onBoot(Application $application): void;
}
