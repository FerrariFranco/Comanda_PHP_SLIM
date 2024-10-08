<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response as SlimResponse;
use AutentificadorJWT;
use Exception;
require_once "./utils/AutentificadorJWT.php";


class CocinaMiddleware
{
    public function __invoke(Request $request, RequestHandlerInterface $handler): Response
    {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        try {
            $datosToken = AutentificadorJWT::ObtenerData($token);

            if ($datosToken->rol != 2 && $datosToken->rol != 4 && $datosToken->rol != 5) {
                $response = new SlimResponse();
                $response->getBody()->write(json_encode([
                    'mensaje' => 'Acceso denegado. Solo los empleados de cocina pueden acceder a este recurso.'
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
