<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use AccesoDatos;

class CSVController
{
    public function exportPedidosTable(Request $request, Response $response, array $args): Response
    {
        $accesoDatos = AccesoDatos::obtenerInstancia();
        $table = 'pedidos';
        $csvContent = $accesoDatos->exportTableToCSV($table);
        
        if (!$csvContent) {
        
            $response->getBody()->write(json_encode(['error' => 'No se pudo exportar la tabla pedidos']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }

        $response = $response->withHeader('Content-Type', 'text/csv')
                             ->withHeader('Content-Disposition', 'attachment; filename="pedidos.csv"');

        $response->getBody()->write($csvContent);

        return $response;
    }
}
