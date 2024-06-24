<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $IdSector = $parametros['IdSector'];
        $capacidad = $parametros['capacidad'];
        $atendida = $parametros['atendida'];
        $estadoMesa = $parametros['estadoMesa'] ?? 'abierta';

        $mesa = new Mesa($IdSector, $capacidad, $atendida, $estadoMesa);
        $mesa->crearMesa();

        $payload = json_encode(array("mensaje" => "Mesa creada con éxito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $mesa = Mesa::obtenerMesa($id);
        $payload = json_encode($mesa);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::obtenerTodas();
        $payload = json_encode(array("listaMesa" => $lista));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];
        $IdSector = $parametros['IdSector'] ?? null;
        $capacidad = $parametros['capacidad'] ?? null;
        $atendida = $parametros['atendida'] ?? null;
        $estadoMesa = $parametros['estadoMesa'] ?? null;

        Mesa::modificarMesa($id, $IdSector, $capacidad, $atendida, $estadoMesa);

        $payload = json_encode(array("mensaje" => "Mesa modificada con éxito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];
        Mesa::borrarMesa($id);

        $payload = json_encode(array("mensaje" => "Mesa borrada con éxito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function CerraMesa($request, $response, $args)
    {
        $id = $args['id'];
        Mesa::cerrarMesa($id);

        $payload = json_encode(array("mensaje" => "Mesa cerrada con éxito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function AbriMesa($request, $response, $args)
    {
        $id = $args['id'];
        Mesa::abrirMesa($id);

        $payload = json_encode(array("mensaje" => "Mesa abierta con éxito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
