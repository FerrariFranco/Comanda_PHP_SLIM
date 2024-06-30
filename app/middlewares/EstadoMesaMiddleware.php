<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response as SlimResponse;

class EstadoMesaMiddleware
{
    public function __invoke(Request $request, RequestHandlerInterface $handler): Response
    {
        if ($request->getMethod() === 'POST') {
            $parsedBody = $request->getParsedBody();

            if (!isset($parsedBody["id"]) || !isset($parsedBody["estadoMesa"])) {
                if($parsedBody["estadoMesa"] =! "ATENDIDA" || $parsedBody["estadoMesa"] =! "DISPONIBLE" || $parsedBody["estadoMesa"] =! "CERRADA" || $parsedBody["estadoMesa"] =! "SERVIDA")
                {
                    $response = new SlimResponse();
                    $response->getBody()->write(json_encode([
                        'error' => "ERROR EN LOS CAMPOS INGRESADOS"
                    ]));
                    return $response->withHeader('Content-Type', 'application/json')
                                    ->withStatus(400);
                    
                }
            }
            
        }

        return $handler->handle($request);
    }
}
