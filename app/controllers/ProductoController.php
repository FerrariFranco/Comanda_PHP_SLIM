<?php

require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $precio = $parametros['precio'];
        $tipo = $parametros['tipo'];

        // Creamos el producto
        $producto = new Producto();
        $producto->nombre = $nombre;
        $producto->precio = $precio;
        $producto->tipo = $tipo;
        $producto->crearProducto();

        $payload = json_encode(array("mensaje" => "Producto creado con éxito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos producto por ID
        $id = $args['id'];
        $producto = Producto::obtenerProducto($id);
        $payload = json_encode($producto);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array($lista));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];
        $nombre = $parametros['nombre'] ?? null;
        $precio = $parametros['precio'] ?? null;
        $tipo = $parametros['idTipo'] ?? null;

        Producto::modificarProducto($id, $nombre, $precio, $tipo);

        $payload = json_encode(array("mensaje" => "Producto modificado con éxito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];
        Producto::borrarProducto($id);

        $payload = json_encode(array("mensaje" => "Producto borrado con éxito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ProductoMasSolicitada($request, $response, $args)
    {
        $ProductoMasSolicitada = Producto::obtenerProductoMasSolicitada();

        if ($ProductoMasSolicitada) {
            $payload = json_encode($ProductoMasSolicitada);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $payload = json_encode(array("mensaje" => "No se encontró ninguna Producto solicitada."));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }
}
