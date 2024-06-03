<?php
//error_reporting(-1);
//ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
// require_once './middlewares/Logger.php';

require_once './controllers/UsuarioController.php';

// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv->safeLoad();

$app = AppFactory::create();

$app->get('/', function ($request, $response, array $args) {
		$response->getBody()->write("a");
return $response;
});
// $app->post('/usuarios', function (Request $request, Response $response, array $args) {
//     $controller = new UsuarioController();
//     return $controller->CargarUno($request, $response, $args);
// });


$app->run();


