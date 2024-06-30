<?php

namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class VerificarImagenMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $uploadedFiles = $request->getUploadedFiles();
        if (empty($uploadedFiles['imagen'])) {
            $response = new Response();
            $payload = json_encode(['mensaje' => 'No se subió ninguna imagen']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $imagen = $uploadedFiles['imagen'];

        if ($imagen->getError() !== UPLOAD_ERR_OK) {
            $response = new Response();
            $payload = json_encode(['mensaje' => 'Error al subir la imagen']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        return $handler->handle($request);
    }
}
