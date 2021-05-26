<?php declare(strict_types=1);

namespace Sergonie\Application\Listeners;

use Sergonie\Application\Application;

/**
 * Can be implemented by module to perform tasks when application has already loaded modules and
 * configuration is loaded.
 *
 * @package Sergonie\Application\Listeners
 */
interface OnRunListener
{
    public function onRun(Application $application): void;
}
