<?php
require_once './models/Tipo.php';
require_once './interfaces/IApiUsable.php';

class TipoController implements IApiUsable
{
    private $tipo;

    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $tipo = $parametros['nombre'];
        $idSector = $parametros['IdSector'];

        // Crear una instancia de Tipo y asignarla a $this->tipo
        $this->tipo = new Tipo();
        $this->tipo->tipo = $tipo;
        $this->tipo->idSector = $idSector;
        $this->tipo->crearTipo();

        $payload = json_encode(array("mensaje" => "Tipo creado con éxito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscar tipo por ID
        $id = $args['id'];
        $tipo = Tipo::obtenerTipo($id);
        $payload = json_encode($tipo);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Tipo::obtenerTodos();
        $payload = json_encode(array("listaTipo" => $lista));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];
        $tipo = $parametros['tipo'] ?? null;
        $idSector = $parametros['idSector'] ?? null;

        // Modificar el tipo utilizando $this->tipo si es necesario
        if ($this->tipo) {
            $this->tipo->modificarTipo($tipo, $idSector);
        } 

        $payload = json_encode(array("mensaje" => "Tipo modificado con éxito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $args['id'];

        Tipo::borrarTipo($id);

        $payload = json_encode(array("mensaje" => "Tipo borrado con éxito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
