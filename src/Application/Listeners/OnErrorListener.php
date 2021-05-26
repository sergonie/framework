<?php declare(strict_types=1);

namespace Sergonie\Application\Listeners;

use Sergonie\Application\Application;

use Throwable;

/**
 * Can be implemented by module to perform tasks when error occurs, exception can be
 * overridden by the handler - this can be useful when there is requirement for
 * displaying custom responses when given exception occurs.
 *
 * @package Sergonie\Application\Listeners
 */
interface OnErrorListener
{
    public function onError(Application $application, Throwable $exception): Throwable;
}
