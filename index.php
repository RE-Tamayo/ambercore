<?php

include __DIR__ . '/vendor/autoload.php';

use Amber\Http\Router;
use Amber\Http\Request;
use Amber\Test;

$request = new Request();

$router = new Router();

$router->add('GET', '/', function() {
    echo "WOW";
});
$router->add('GET', '/profile/{name}', [Test::class, 'test']);


// $router->add('GET', '/{name}', function($name) {
//     echo $name;
// });

// $router->add('GET', '/test', ['test']);
// $router->add('GET', '/slug/{slug}/test/{name}', ['testjhgfjfgf']);

$router->run($request);
