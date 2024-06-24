<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use AccesoDatos;

class CSVController
{
    public function exportCSV(Request $request, Response $response, $args): Response
    {
        $accesoDatos = AccesoDatos::obtenerInstancia();
        $directory = __DIR__ . '/../../exported_csv';
        $accesoDatos->exportAllTablesToCSV($directory);

        $payload = json_encode(['mensaje' => 'Las tablas se han exportado a archivos CSV correctamente.']);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
