<?php

require_once getcwd() . '/../vendor/autoload.php';

use App\Modules\Configuration\Router;

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'] ?? '/';

$router = new Router($method, $path);

$router->add('GET', '/', function ($params) {
    return '';
});


$result = $router->handler();

if (!$result) {
    http_response_code(404);
    //TODO: Criar 404 page
    die();
}

echo $result($router->get('params'));