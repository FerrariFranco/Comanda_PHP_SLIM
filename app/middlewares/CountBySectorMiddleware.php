<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
require_once "./db/accesodatos.php";

class CountBySectorMiddleware {
    public function __invoke(Request $request, Response $response, callable $next) {
        $parsedBody = $request->getParsedBody();
        $sectorId = $parsedBody['sector_id'] ?? null;

        if ($sectorId) {
            
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("UPDATE sector_operaciones SET cantidad = cantidad + 1 WHERE sector_id = :sector_id");
            $consulta->bindValue(':sector_id', $sectorId, PDO::PARAM_INT);
            $consulta->execute();
        }

        return $next($request, $response);
    }
    
}
