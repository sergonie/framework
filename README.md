[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](./LICENSE)
[![Build Status](https://travis-ci.org/igniphp/framework.svg?branch=master)](https://travis-ci.org/igniphp/framework)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sergonie/framework/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sergonie/framework/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/sergonie/framework/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/sergonie/framework/?branch=master)

Sergonie is a php7 anti-framework with built-in [swoole server](https://www.swoole.co.uk) and modular architecture support to 
help you quickly write scalable PSR-7 and PSR-15 compilant REST services.

Its main objective it to be as much transparent and as less visible for your application as possible.

```php
<?php
require 'vendor/autoload.php';

use Sergonie\Application\Config;
use Sergonie\Application\HttpApplication;
use Sergonie\Application\Providers\ConfigProvider;
use Sergonie\Network\Http\Response;
use Sergonie\Network\Http\Request;

$application = new HttpApplication();

// Routing
$application->get('/hello/{name}', function (Request $request) : Response {
    return Response::asText("Hello {$request->getAttribute('name')}.");
});

// Middleware - no interfaces no binding with framework code is required in order things to work.
$application->use(function($request, /** callable|RequestHandlerInterface */$next) {
    $response = $next($request);
    return $response->withAddedHeader('Version', $this->getConfig()->get('version'));
});

// Extending application is a brief just create and implement methods for your needs.
$application->extend(new class implements ConfigProvider {
    public function provideConfig(Config $config): void {
        $config->set('version', '1.0');
    }
});

$application->run();
```

## Installation and requirements

Recommended installation way of the Sergonie Framework is with composer:

``` 
composer install sergonie/framework
```

Requirements:
 - php 7.4 or better
 - [swoole](https://github.com/swoole/swoole-src) extension for build-in http server support

### What's new

With version 2.0 network package was extracted from the framework code, error handling was 
overall improved as well as Server's listeners. More details can be found in changelog file.

### Quick start
Alternatively you can start using framework with [quick start](https://github.com/sergonie/framework-quick-start) which contains bootstrap application.

## Features

### Routing

Sergonie router is based on very fast symfony routing library.

### PSR-7, PSR-15 Support

Sergonie fully supports PSR message standards for both manipulating http response, request and http middlwares.

### Dependency Injection and Autoresolving

Sergonie autoresolves dependencies for you and provides intuitive dependency container. 
It also allows you to use any PSR compatible container of your choice.

### Modular architecture

Modular and scalable solution is one of the most important aspects why this framework was born.
Simply create a module class, implement required interfaces and extend application by your module.

### Performant, production ready http server

No nginx nor apache is required when `swoole` is installed, application can be run the same manner as in node.js world:
 ``` 
php examples/build_in_server_example.php
 ```
 
Sergonie's http server is as fast as express.js application with almost 0 configuration. 

### Detailed documentation

Detailed documentation and more examples can be [found here](docs/README.md) and in examples directory.
