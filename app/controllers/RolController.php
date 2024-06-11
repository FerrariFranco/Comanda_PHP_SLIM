<?php
require_once './models/Rol.php';
require_once './interfaces/IApiUsable.php';

class RolController extends Rol implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $rol = $parametros['rol'];

        // Creamos el rol
        $rol = new Rol($rol);
        $rol->crearRol();

        $payload = json_encode(array("mensaje" => "Rol creado con éxito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos rol por ID
        $id = $args['id'];
        $rol = Rol::obtenerRol($id);
        $payload = json_encode($rol);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Rol::obtenerTodos();
        $payload = json_encode(array("listaRol" => $lista));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];
        $rol = $parametros['rol'] ?? null;

        Rol::modificarRol($id, $rol);

        $payload = json_encode(array("mensaje" => "Rol modificado con éxito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];
        Rol::borrarRol($id);

        $payload = json_encode(array("mensaje" => "Rol borrado con éxito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
