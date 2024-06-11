<?php
require_once './models/Sector.php';
require_once './interfaces/IApiUsable.php';

class SectorController extends Sector implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $sector = $parametros['sector'];

        // Creamos el sector
        $sector = new Sector($sector);
        $sector->crearSector();

        $payload = json_encode(array("mensaje" => "Sector creado con éxito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos sector por ID
        $id = $args['id'];
        $sector = Sector::obtenerSector($id);
        $payload = json_encode($sector);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Sector::obtenerTodos();
        $payload = json_encode(array("listaSector" => $lista));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];
        $sector = $parametros['sector'] ?? null;

        Sector::modificarSector($id, $sector);

        $payload = json_encode(array("mensaje" => "Sector modificado con éxito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];
        Sector::borrarSector($id);

        $payload = json_encode(array("mensaje" => "Sector borrado con éxito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
