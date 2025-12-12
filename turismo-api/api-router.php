<?php
require_once 'libs/router.php';

require_once 'controllers/DestinoApiController.php';
require_once 'controllers/AuthApiController.php';

require_once 'middlewares/jwt.middleware.php';
require_once 'middlewares/guard.middleware.php';

$router = new Router();

$router->addMiddleware(new JWTMiddleware());

//RUTAS PÚBLICAS

$router->addRoute('auth/token',   'GET', 'AuthApiController',   'getToken');

$router->addRoute('destinos',     'GET', 'DestinoApiController', 'getAll');
$router->addRoute('destinos/:ID', 'GET', 'DestinoApiController', 'getDestino');


$router->addMiddleware(new GuardMiddleware());


//RUTAS PROTEGIDAS

$router->addRoute('destinos',     'POST',   'DestinoApiController', 'createDestino');
$router->addRoute('destinos/:ID', 'PUT',    'DestinoApiController', 'updateDestino');
$router->addRoute('destinos/:ID', 'DELETE', 'DestinoApiController', 'deleteDestino');


// Ejecución
$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);
