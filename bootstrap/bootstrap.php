<?php

require_once getcwd() . '/../vendor/autoload.php';

use App\Controllers\HomeController;
use App\Modules\Configuration\Router;

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'] ?? '/';

$router = new Router($method, $path);

$router->add('GET', '/', function ($params) {
    return HomeController::home();
});

$result = $router->handler();

if (!$result) {
    http_response_code(404);
    echo "Erro 404";
    die();
}

echo $result($router->get('params'));