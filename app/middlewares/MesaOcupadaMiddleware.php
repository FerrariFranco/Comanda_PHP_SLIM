<?php

namespace App\Middlewares;

use Mesa;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;
require_once "./models/mesa.php";
class MesaOcupadaMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $params = (array)$request->getParsedBody();
        $idMesa = $params['idMesa'] ?? null;

        if ($idMesa === null) {
            $response = new SlimResponse();
            $payload = json_encode(['mensaje' => 'ID de mesa no proporcionado']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $mesa = Mesa::obtenerMesa($idMesa);
        if (!$mesa) {
            $response = new SlimResponse();
            $payload = json_encode(['mensaje' => 'Mesa no encontrada']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        if ($mesa->estadoMesa != 'DISPONIBLE') {
            $response = new SlimResponse();
            $payload = json_encode(['mensaje' => 'La mesa está ocupada']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
        }

        return $handler->handle($request);
    }
}
