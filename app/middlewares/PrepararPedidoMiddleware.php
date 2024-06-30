<?php

namespace App\Middlewares;

use PedidoProducto;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response as SlimResponse;
use AutentificadorJWT;
use Exception;
require_once "./utils/AutentificadorJWT.php";
require_once "./models/PedidoProducto.php";


class PrepararPedidoMiddleware
{
    public function __invoke(Request $request, RequestHandlerInterface $handler): Response
    {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $parsedBody = $request->getParsedBody();

        $PedidoProducto = PedidoProducto::obtenerPedidoProducto($parsedBody["id"]);
        $idProducto = $PedidoProducto->idProducto;
        $s = PedidoProducto::obtenerSectorResponsable($idProducto);
        echo $s;

        try {
            $datosToken = AutentificadorJWT::ObtenerData($token);

            if ($datosToken->sector != $s) {
                $response = new SlimResponse();
                $response->getBody()->write(json_encode([
                    'mensaje' => 'Acceso denegado. Este pedido no es de tu sector'
                ]));
                return $response->withHeader('Content-Type', 'application/json')
                                ->withStatus(403);
            }

            return $handler->handle($request);
        } catch (Exception $e) {
            $response = new SlimResponse();
            $payload = json_encode(array('mensaje' => 'ERROR: Hubo un error con el TOKEN'));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')
                            ->withStatus(401);
        }
    }
}
