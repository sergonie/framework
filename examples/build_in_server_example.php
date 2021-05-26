<?php
require_once __DIR__.'/../vendor/autoload.php';

use Igni\Application\HttpApplication;
use Sergonie\Network\Http\Response;
use Sergonie\Network\Server\Configuration;
use Sergonie\Network\Server\HttpServer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

// Setup server
$server = new HttpServer(new Configuration(8080, '0.0.0.0'));

// Setup application and routes
$application = new HttpApplication();
$application->get('/hello/{name}', function (ServerRequestInterface $request) : ResponseInterface {
    return Response::asText("Hello {$request->getAttribute('name')}");
});

// Run the server, it should listen on localhost:8080
$application->run($server);
