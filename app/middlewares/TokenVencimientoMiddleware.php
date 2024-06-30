<?php

namespace App\Middlewares;

use AccesoDatos;
use PDO;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;

require_once "./db/accesodatos.php";

class TokenVencimientoMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $params = $request->getParsedBody();
        $usuario = $params['usuario'] ?? '';

        if (empty($usuario)) {
            $response = new SlimResponse();
            $response->getBody()->write(json_encode(['mensaje' => 'Falta el parámetro de usuario']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $db = AccesoDatos::obtenerInstancia();
        $consulta = $db->prepararConsulta("SELECT creado_en, vence_el FROM tokens WHERE usuario = :usuario");
        $consulta->bindParam(':usuario', $usuario);
        $consulta->execute();

        if ($consulta->rowCount() > 0) {
            
            $token = $consulta->fetch(PDO::FETCH_ASSOC);
            $ahora = time();
            $vence_el = (int)$token['vence_el'];
            
            // Calcula la diferencia en segundos
            $diferencia = $vence_el - $ahora;
            echo $diferencia;
            if ($diferencia > 0) {
                $response = new SlimResponse();
                $response->getBody()->write(json_encode(['mensaje' => 'Ya existe un token válido para este usuario']));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }

        return $handler->handle($request);
    }
}
