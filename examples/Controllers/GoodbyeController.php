<?php
namespace Examples\Controllers;

use Sergonie\Application\Http\Controller;
use Sergonie\Network\Http\Response;
use Sergonie\Network\Http\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GoodbyeController implements Controller
{
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return Response::asText('Goodbye cruel world!');
    }

    public static function getRoute(): Route
    {
        return Route::get('/goodbye');
    }
}
