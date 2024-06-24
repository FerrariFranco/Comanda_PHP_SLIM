<?php

class Middleware
{
    public static function verificarParametrosNoVacios($request, $handler)
    {
        $body = $request->getParsedBody();
        $queryParams = $request->getQueryParams();

        $params = array_merge($body, $queryParams);
        foreach ($params as $key => $value) {
            if (empty($value)) {
                $response = new \Slim\Psr7\Response();
                $response->getBody()->write(json_encode(['error' => "El parámetro '$key' no puede estar vacío"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }

        return $handler->handle($request);
    }
}
