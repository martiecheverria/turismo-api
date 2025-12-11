<?php
require_once 'libs/router.php';
require_once 'controllers/DestinoApiController.php';
require_once 'controllers/RegionApiController.php';

$router = new Router();


$router->addRoute('destinos',     'GET',    'DestinoApiController', 'getAll');

$router->addRoute('destinos/:ID', 'GET',    'DestinoApiController', 'getDestino');

$router->addRoute('destinos',     'POST',   'DestinoApiController', 'createDestino');

$router->addRoute('destinos/:ID', 'PUT',    'DestinoApiController', 'updateDestino');

$router->addRoute('destinos/:ID', 'DELETE', 'DestinoApiController', 'deleteDestino');



$resource = $_GET['resource'];
$method = $_SERVER['REQUEST_METHOD'];
$router->route($resource, $method);