<?php

require_once './models/Encuesta.php';
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
class EncuestaController
{
    public function crearEncuesta($request, $response, $args)
{
    $params = $request->getParsedBody();
    $idPedido = $params['idPedido'] ?? null;
    $idMesa = $params['idMesa'] ?? null;
    $puntuacion = $params['puntuacion'] ?? null;
    $comentario = $params['comentario'] ?? null;

    if (!$idMesa || !$puntuacion || !$comentario || !$idPedido) {
        $payload = json_encode(array("mensaje" => "Faltan datos requeridos"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    $encuesta = new Encuesta($idMesa, $idPedido, $puntuacion, $comentario);
    $encuesta_id = $encuesta->guardar();

    $payload = json_encode(array("mensaje" => "Encuesta creada con éxito. ID: $encuesta_id"));
    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json');
}

    public function obtenerEncuesta($request, $response, $args)
    {
        $id = $args['id'];

        $encuesta = Encuesta::buscarPorId($id);

        if (!$encuesta) {
            $payload = json_encode(array("mensaje" => "Encuesta no encontrada"));
            return $response->withStatus(404)->getBody()->write($payload);
        }

        $payload = json_encode($encuesta);
        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function obtenerMejoresEncuestas($request, $response, $args)
    {
        $cantidad = 5;
        $mejoresEncuestas = Encuesta::obtenerMejoresEncuestas();

        $payload = json_encode($mejoresEncuestas);
        $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json');
    }

    public function obtenerPeoresEncuestas($request, $response, $args)
    {
        $cantidad = 5;
        $peoresEncuestas = Encuesta::obtenerPeoresEncuestas();

        $payload = json_encode($peoresEncuestas);
        $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json');
    }

    public function descargarMejoresEncuestasPDF($request, $response, $args)
    {
        Encuesta::crearPDFMejoresEncuestas();

        $payload = json_encode(array("mensaje" => "PDF de las mejores encuestas creado con éxito"));
        $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json');
    }
}

