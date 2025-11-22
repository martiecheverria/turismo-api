<?php
require_once 'libs/router.php';
require_once 'controllers/DestinoApiController.php';

$router = new Router();

$router->addRoute('destinos', 'GET', 'DestinoApiController', 'getDestinos');
$router->addRoute('destinos/:ID', 'GET', 'DestinoApiController', 'getDestino');


$resource = $_GET['resource'];
$method = $_SERVER['REQUEST_METHOD'];
$router->route($resource, $method);