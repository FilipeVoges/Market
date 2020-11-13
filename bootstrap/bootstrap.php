<?php

require_once getcwd() . '/../vendor/autoload.php';

use App\Controllers\HomeController;
use App\Controllers\ProductController;
use App\Controllers\RateController;
use App\Controllers\SaleController;
use App\Controllers\TypeController;
use App\Modules\Configuration\Router;

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'] ?? '/';

$router = new Router($method, $path);

$router->add('GET', '/', function ($params) {
    return (new HomeController)->home();
});

$router->add('GET', '/products', function ($params) {
    return (new ProductController)->home();
});

$router->add('POST', '/products', function ($params) {
    return (new ProductController)->register();
});

$router->add('GET', '/types', function ($params) {
    return (new TypeController)->home();
});

$router->add('POST', '/types', function ($params) {
    return (new TypeController)->register();
});

$router->add('GET', '/rates', function ($params) {
    return (new RateController)->home();
});

$router->add('POST', '/rates', function ($params) {
    return (new RateController)->register();
});

$router->add('GET', '/sales', function ($params) {
    return (new SaleController)->home();
});

$router->add('POST', '/sales', function ($params) {
    return (new SaleController)->register();
});

$router->add('GET', '/s{id}', function ($params) {
    $id = isset($params[1]) ? $params[1] : null;
    return (new SaleController)->sale($id);
});

$router->add('POST', '/s{id}', function ($params) {
    $id = isset($params[1]) ? $params[1] : null;
    return (new SaleController)->addProduct($id);
});

$router->add('POST', '/add', function ($params) {
    return (new SaleController)->registerProduct();
});

$result = $router->handler();

if (!$result) {
    http_response_code(404);
    echo "Erro 404";
    die();
}

echo $result($router->get('params'));