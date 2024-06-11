<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/TipoController.php';
require_once './controllers/SectorController.php';
require_once './controllers/RolController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/PedidoProductoController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/MesaController.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$app = AppFactory::create();

$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->post('/', UsuarioController::class . ':CargarUno');
    $group->get('/{id}', UsuarioController::class . ':TraerUno');
    $group->get('/', UsuarioController::class . ':TraerTodos');
    $group->put('/', UsuarioController::class . ':ModificarUno');
    $group->delete('/{id}', UsuarioController::class . ':BorrarUno');
});

$app->group('/tipos', function (RouteCollectorProxy $group) {
    $group->post('/', TipoController::class . ':CargarUno');
    $group->get('/{id}', TipoController::class . ':TraerUno');
    $group->get('/', TipoController::class . ':TraerTodos');
    $group->put('/', TipoController::class . ':ModificarUno');
    $group->delete('/{id}', TipoController::class . ':BorrarUno');
});

$app->group('/sectores', function (RouteCollectorProxy $group) {
    $group->post('/', SectorController::class . ':CargarUno');
    $group->get('/{id}', SectorController::class . ':TraerUno');
    $group->get('/', SectorController::class . ':TraerTodos');
    $group->put('/', SectorController::class . ':ModificarUno');
    $group->delete('/{id}', SectorController::class . ':BorrarUno');
});

$app->group('/roles', function (RouteCollectorProxy $group) {
    $group->post('/', RolController::class . ':CargarUno');
    $group->get('/{id}', RolController::class . ':TraerUno');
    $group->get('/', RolController::class . ':TraerTodos');
    $group->put('/', RolController::class . ':ModificarUno');
    $group->delete('/{id}', RolController::class . ':BorrarUno');
});

$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->post('/', ProductoController::class . ':CargarUno');
    $group->get('/{id}', ProductoController::class . ':TraerUno');
    $group->get('/', ProductoController::class . ':TraerTodos');
    $group->put('/', ProductoController::class . ':ModificarUno');
    $group->delete('/{id}', ProductoController::class . ':BorrarUno');
});

$app->group('/pedido_productos', function (RouteCollectorProxy $group) {
    $group->post('/', PedidoProductoController::class . ':CargarUno');
    $group->get('/{id}', PedidoProductoController::class . ':TraerUno');
    $group->get('/', PedidoProductoController::class . ':TraerTodos');
    $group->put('/', PedidoProductoController::class . ':ModificarUno');
    $group->delete('/{id}', PedidoProductoController::class . ':BorrarUno');
});

$app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->post('/', PedidoController::class . ':CargarUno');
    $group->get('/{id}', PedidoController::class . ':TraerUno');
    $group->get('/', PedidoController::class . ':TraerTodos');
    $group->put('/', PedidoController::class . ':ModificarUno');
    $group->post('/agregarProducto', PedidoController::class . ':AgregarProducto');
    $group->put('/entregar/{id}', PedidoController::class . ':EntregarPedido');
    $group->delete('/{id}', PedidoController::class . ':BorrarUno');
});

$app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->post('/', MesaController::class . ':CargarUno');
    $group->get('/{id}', MesaController::class . ':TraerUno');
    $group->get('/', MesaController::class . ':TraerTodos');
    $group->put('/', MesaController::class . ':ModificarUno');
    $group->delete('/{id}', MesaController::class . ':BorrarUno');
});

$app->run();