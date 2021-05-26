<?php declare(strict_types=1);

namespace Sergonie\Tests\Fixtures;

use Sergonie\Application\Http\Controller;
use Sergonie\Network\Http\Route;
use Sergonie\Network\Http\Response;
use Sergonie\Network\Http\Route as IgniNetworkHttpRoute;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HttpController implements Controller
{
    public const URI = '/testhttpcontroller';

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return Response::asText('test controller');
    }

    public static function getRoute(): IgniNetworkHttpRoute
    {
        return Route::get(self::URI);
    }
}
