<?php declare(strict_types=1);

namespace Sergonie\Application\Http;

use Sergonie\Application\Controller as ApplicationController;
use Sergonie\Network\Http\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Represents generic application's controller.
 *
 * Because php does not support generic types, empty interface should be good enough
 * to provide consistency in the application flow for controller handling.
 *
 * @package Sergonie\Application
 */
interface Controller extends ApplicationController
{
    public function __invoke(ServerRequestInterface $request): ResponseInterface;

    public static function getRoute(): Route;
}
