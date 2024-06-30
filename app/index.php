<?php
use App\Controllers\CSVController;
use App\Middlewares\AdminMiddleware;
use App\Middlewares\LogMiddleware;
use App\Controllers\AuthController;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\MozoMiddleware;
use App\Middlewares\CocinaMiddleware;
use App\Middlewares\VerifyPostFieldsMiddleware;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use App\Middlewares\VerificarImagenMiddleware;
use App\Middlewares\TokenVencimientoMiddleware;
use App\Middlewares\MesaOcupadaMiddleware;
use App\Middlewares\PrepararPedidoMiddleware;





require __DIR__ . '/../vendor/autoload.php';
require_once './db/AccesoDatos.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/TipoController.php';
require_once './controllers/AuthController.php';
require_once './controllers/SectorController.php';
require_once './controllers/RolController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/PedidoProductoController.php';
require_once "./controllers/EncuestaController.php";
require_once './controllers/PedidoController.php';
require_once './controllers/MesaController.php';
require_once './controllers/CSVController.php';
require_once './middlewares/AuthMiddleware.php';
require_once './middlewares/LogMiddleware.php';
require_once './middlewares/MozoMiddleware.php';
require_once './middlewares/AdminMiddleware.php';
require_once './middlewares/CountBySectorMiddleware.php';
require_once './middlewares/CountByUserMiddleware.php';
require_once './middlewares/VerifyPostFieldsMiddleware.php';
require_once './middlewares/VerificarImagenMiddleware.php';
require_once './middlewares/MesaOcupadaMiddleware.php';
require_once './middlewares/PrepararPedidoMiddleware.php';
require_once './middlewares/CocinaMiddleware.php';
require_once './middlewares/TokenVencimientoMiddleware.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$app = AppFactory::create();

// $app->add(new LogMiddleware());

$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->post('/cargar', UsuarioController::class . ':CargarUno')->add(new VerifyPostFieldsMiddleware());
    $group->get('/{id}', UsuarioController::class . ':TraerUno')->add(new AdminMiddleware())->add(new AuthMiddleware());
    $group->get('/', UsuarioController::class . ':TraerTodos')->add(new AdminMiddleware())->add(new AuthMiddleware());
    $group->put('/', UsuarioController::class . ':ModificarUno')->add(new AdminMiddleware())->add(new AuthMiddleware());
    $group->delete('/{id}', UsuarioController::class . ':BorrarUno')->add(new AdminMiddleware())->add(new AuthMiddleware());
    $group->get('/{id}/pedidos', UsuarioController::class . ':ObtenerPedidosPorUsuario')->add(new AdminMiddleware())->add(new AuthMiddleware());
});

$app->group('/tipos', function (RouteCollectorProxy $group) {
    $group->post('/', TipoController::class . ':CargarUno')->add(new AdminMiddleware());
    $group->get('/{id}', TipoController::class . ':TraerUno')->add(new AdminMiddleware());
    $group->get('/', TipoController::class . ':TraerTodos')->add(new AdminMiddleware());
    $group->put('/', TipoController::class . ':ModificarUno')->add(new AdminMiddleware());
    $group->delete('/{id}', TipoController::class . ':BorrarUno')->add(new AdminMiddleware());
})->add(new AuthMiddleware());

$app->group('/sectores', function (RouteCollectorProxy $group) {
    $group->post('/', SectorController::class . ':CargarUno')->add(new AdminMiddleware());
    $group->get('/{id}', SectorController::class . ':TraerUno')->add(new AdminMiddleware());
    $group->get('/', SectorController::class . ':TraerTodos')->add(new AdminMiddleware());
    $group->put('/', SectorController::class . ':ModificarUno')->add(new AdminMiddleware());
    $group->delete('/{id}', SectorController::class . ':BorrarUno')->add(new AdminMiddleware());
})->add(new AuthMiddleware());

$app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->post('/', MesaController::class . ':CargarUno')->add(new AdminMiddleware());
    $group->get('/traer/{id}', MesaController::class . ':TraerUno')->add(new AdminMiddleware());
    $group->get('/', MesaController::class . ':TraerTodos')->add(new AdminMiddleware());
    $group->put('/', MesaController::class . ':ModificarUno')->add(new AdminMiddleware());
    $group->delete('/eliminar/{id}', MesaController::class . ':BorrarUno')->add(new AdminMiddleware());
    $group->get('/mas-solicitada', MesaController::class . ':MesaMasSolicitada')->add(new AdminMiddleware());

})->add(new AuthMiddleware());

$app->group('/roles', function (RouteCollectorProxy $group) {
    $group->post('/', RolController::class . ':CargarUno')->add(new AdminMiddleware());
    $group->get('/{id}', RolController::class . ':TraerUno')->add(new AdminMiddleware());
    $group->get('/', RolController::class . ':TraerTodos')->add(new AdminMiddleware());
    $group->put('/', RolController::class . ':ModificarUno')->add(new AdminMiddleware());
    $group->delete('/{id}', RolController::class . ':BorrarUno')->add(new AdminMiddleware());
})->add(new AuthMiddleware());

$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->post('/', ProductoController::class . ':CargarUno')->add(new AdminMiddleware())->add(new AuthMiddleware());
    $group->get('/{id}', ProductoController::class . ':TraerUno');
    $group->get('/', ProductoController::class . ':TraerTodos');
    $group->put('/', ProductoController::class . ':ModificarUno')->add(new AdminMiddleware())->add(new AuthMiddleware());
    $group->delete('/{id}', ProductoController::class . ':BorrarUno')->add(new AdminMiddleware())->add(new AuthMiddleware());
});

$app->group('/pedido_productos', function (RouteCollectorProxy $group) {
    $group->post('/', PedidoController::class . ':AgregarProducto')->add(new MozoMiddleware())->add(new AuthMiddleware());
    $group->get('/{id}', PedidoProductoController::class . ':TraerUno');
    $group->get('/servir/{id}', PedidoProductoController::class . ':Servir')->add(new MozoMiddleware())->add(new AuthMiddleware());
    $group->post('/preparar', PedidoProductoController::class . ':Preparar')->add(new PrepararPedidoMiddleware())->add(new CocinaMiddleware())->add(new AuthMiddleware());
    $group->get('/', PedidoProductoController::class . ':TraerTodos');
    $group->put('/', PedidoProductoController::class . ':ModificarUno');
    $group->delete('/{id}', PedidoProductoController::class . ':BorrarUno');
});

$app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->post('/', PedidoController::class . ':CargarUno')->add(new MesaOcupadaMiddleware())->add(new MozoMiddleware())->add(new AuthMiddleware());
    $group->get('/{id}', PedidoController::class . ':TraerUno');
    $group->get('/', PedidoController::class . ':TraerTodos');
    $group->get('/cocina/{id}', PedidoProductoController::class . ':TraerPedidosPorSector')->add(new CocinaMiddleware())->add(new AuthMiddleware());
    $group->get('/candybar/{id}', PedidoProductoController::class . ':TraerPedidosPorSector')->add(new CocinaMiddleware())->add(new AuthMiddleware());
    $group->get('/barra/{id}', PedidoProductoController::class . ':TraerPedidosPorSector')->add(new CocinaMiddleware())->add(new AuthMiddleware());
    $group->put('/', PedidoController::class . ':ModificarUno')->add(new MozoMiddleware())->add(new AuthMiddleware());
    $group->post('/agregarProducto', PedidoController::class . ':AgregarProducto')->add(new MozoMiddleware())->add(new AuthMiddleware());
    $group->get('/cobrar/{id}', PedidoController::class . ':cobrarPedido')->add(new MozoMiddleware())->add(new AuthMiddleware());
    $group->delete('/{id}', PedidoController::class . ':BorrarUno');
    $group->get('/{id}/estado', PedidoController::class . ':obtenerEstado');

});

$app->group('/encuestas', function (RouteCollectorProxy $group) {
    $group->post('/', EncuestaController::class . ':crearEncuesta');
    $group->get('/buscar/{id}', EncuestaController::class . ':obtenerEncuestaPorId')->add(new AdminMiddleware());
    $group->get('/mejores', EncuestaController::class . ':obtenerMejoresEncuestas')->add(new AdminMiddleware());
    $group->get('/peores', EncuestaController::class . ':obtenerPeoresEncuestas')->add(new AdminMiddleware());
    $group->get('/pdf', EncuestaController::class . ':descargarMejoresEncuestasPDF')->add(new AdminMiddleware());
})->add(new AuthMiddleware()); 


$app->post('/login', AuthController::class . ':login')->add(new TokenVencimientoMiddleware());
$app->get('/csv', CSVController::class . ':exportCSV')->add(new AdminMiddleware())->add(new AuthMiddleware());

$app->post('/pedido/imagen', PedidoController::class . ':guardarLaImagen')->add(new VerificarImagenMiddleware())->add(new MozoMiddleware())->add(new AuthMiddleware());


$app->run();