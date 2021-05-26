<?php
namespace Examples\Modules;

use Examples\Controllers\GoodbyeController;
use Igni\Application\ControllerAggregator;
use Igni\Application\HttpApplication;
use Igni\Application\Providers\ControllerProvider;
use Sergonie\Network\Http\Response;
use Sergonie\Network\Http\Route;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Module definition.
 */
class SimpleModule implements ControllerProvider
{
    /**
     * @param HttpApplication|ControllerAggregator $controllers
     */
    public function provideControllers(ControllerAggregator $controllers): void
    {
        $controllers->register(function (ServerRequestInterface $request) {
            return Response::asText("Hello {$request->getAttribute('name')}!");
        }, Route::get('/hello/{name}'));

        $controllers->register(GoodbyeController::class);
    }
}
