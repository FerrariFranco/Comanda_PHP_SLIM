<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use AutentificadorJWT;
use Usuario;
require_once "./utils/AutentificadorJWT.php";

class AuthController
{
    public function login(Request $request, Response $response, array $args): Response
    {
        $params = $request->getParsedBody();
        $usuario = $params['usuario'] ?? '';
        $clave = $params['clave'] ?? '';

        $user = Usuario::obtenerUsuarioPorCredenciales($usuario, $clave);
        echo $user->rol;

        if ($user) {
            $token = AutentificadorJWT::CrearToken(['id' => $user->id, 'usuario' => $user->usuario, 'rol' => $user->rol, 'sector' => $user->sector]);
            $payload = json_encode(['token' => $token]);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $payload = json_encode(['mensaje' => 'Usuario o clave incorrecta']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
    }
}
