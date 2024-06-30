<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $IdSector = $parametros['idSector'];
        $capacidad = $parametros['capacidad'];

        $mesa = new Mesa();
        $mesa->IdSector = $IdSector;
        $mesa->capacidad = $capacidad;
        $mesa->crearMesa();

        $payload = json_encode(array("mensaje" => "Mesa creada con éxito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];
        $IdSector = $parametros['IdSector'] ?? null;
        $capacidad = $parametros['capacidad'] ?? null;
        $atendida = $parametros['atendida'] ?? null;
        $estadoMesa = $parametros['estadoMesa'] ?? null;

        // Llama a modificarMesa con los argumentos esperados por su definición
        Mesa::modificarMesa($id, $IdSector, $capacidad, $atendida, $estadoMesa);

        $payload = json_encode(array("mensaje" => "Mesa modificada con éxito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
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

    public function EstadoMesa($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $estadoMesa = $parametros["estadoMesa"];
        Mesa::CambiarEstado($id, $estadoMesa);

        $payload = json_encode(array("mensaje" => "Accion realizada con éxito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function MesaMasSolicitada($request, $response, $args)
    {
        $mesaMasSolicitada = Mesa::obtenerMesaMasSolicitada();

        if ($mesaMasSolicitada) {
            $payload = json_encode($mesaMasSolicitada);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $payload = json_encode(array("mensaje" => "No se encontró ninguna mesa solicitada."));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }
}
