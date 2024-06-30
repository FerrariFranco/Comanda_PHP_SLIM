<?php

namespace App\Middlewares;

use AccesoDatos;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response as SlimResponse;
use PDO;
require_once "./db/accesodatos.php";
class AgregarPedidoProductoMiddleware
{
    private $db;


    public function __invoke(Request $request, RequestHandlerInterface $handler): Response
    {
        if ($request->getMethod() === 'POST') {
            $parsedBody = $request->getParsedBody();

            if (!isset($parsedBody["idPedido"]) || !isset($parsedBody["idProducto"]) || !isset($parsedBody["tiempoPreparacion"])) {
                $response = new SlimResponse();
                $response->getBody()->write(json_encode([
                    'error' => "ERROR EN LOS CAMPOS INGRESADOS"
                ]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }

            $idPedido = $parsedBody["idPedido"];
            $idProducto = $parsedBody["idProducto"];

            if (!$this->existePedido($idPedido) || !$this->existeProducto($idProducto)) {
                $response = new SlimResponse();
                $response->getBody()->write(json_encode([
                    'error' => "El id del pedido o producto no existe"
                ]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }

        return $handler->handle($request);
    }

    private function existePedido($idPedido)
    {
        $db = AccesoDatos::obtenerInstancia();
        $consulta = $db->prepararConsulta("SELECT 1 FROM pedidos WHERE id = :idPedido");
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetch() !== false;
    }

    private function existeProducto($idProducto)
    {
        $db = AccesoDatos::obtenerInstancia();

        $consulta = $db->prepararConsulta("SELECT 1 FROM productos WHERE id = :idProducto");
        $consulta->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetch() !== false;
    }
}
