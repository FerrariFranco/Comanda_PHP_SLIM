<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response as SlimResponse;

class VerifyPostFieldsMiddleware
{
    public function __invoke(Request $request, RequestHandlerInterface $handler): Response
    {
        if ($request->getMethod() === 'POST') {
            $parsedBody = $request->getParsedBody();

            $requiredFields = ['usuario', 'clave', 'rol', 'sector', 'nombre'];
            foreach ($requiredFields as $field) {
                if (!isset($parsedBody[$field])) {
                    $response = new SlimResponse();
                    $response->getBody()->write(json_encode([
                        'error' => "El campo '$field' es obligatorio."
                    ]));
                    return $response->withHeader('Content-Type', 'application/json')
                                    ->withStatus(400);
                }
            }
        }

        // Si todos los campos están presentes, continuar con el manejo normal de la solicitud
        return $handler->handle($request);
    }
}
