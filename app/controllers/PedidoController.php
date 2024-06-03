<?php
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $idMesa = $parametros['idMesa'];
        $productos = $parametros['productos']; // This should be an array of product IDs
        $precioTotal = $parametros['precioTotal'];
        $cobrado = $parametros['cobrado'];

        // Creamos el pedido
        $pedido = new Pedido();
        $pedido->idMesa = $idMesa;
        $pedido->precioTotal = $precioTotal;
        $pedido->cobrado = $cobrado;

        // Create an array of Product objects based on product IDs
        $pedido->listaProductos = [];
        foreach ($productos as $idProducto) {
            $producto = Producto::obtenerProducto($idProducto);
            if ($producto) {
                $pedido->listaProductos[] = $producto;
            }
        }

        $pedido->crearPedido();

        $payload = json_encode(array("mensaje" => "Pedido creado con éxito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $pedido = Pedido::obtenerPedido($id);
        $payload = json_encode($pedido);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Pedido::obtenerTodos();
        $payload = json_encode(array("listaPedidos" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
{
    $id = $args['id'];
    $parametros = $request->getParsedBody();

    // Obtener el pedido existente
    $pedido = Pedido::obtenerPedido($id);

    if (!$pedido) {
        $payload = json_encode(array("mensaje" => "El pedido no existe"));
        return $response->withStatus(404)->getBody()->write($payload);
    }

    // Actualizar los campos modificables
    if (isset($parametros['idMesa'])) {
        $pedido->idMesa = $parametros['idMesa'];
    }
    if (isset($parametros['productos'])) {
        $productos = $parametros['productos']; // Array de IDs de productos
        $pedido->listaProductos = [];
        foreach ($productos as $idProducto) {
            $producto = Producto::obtenerProducto($idProducto);
            if ($producto) {
                $pedido->listaProductos[] = $producto;
            }
        }
    }
    if (isset($parametros['precioTotal'])) {
        $pedido->precioTotal = $parametros['precioTotal'];
    }
    if (isset($parametros['cobrado'])) {
        $pedido->cobrado = $parametros['cobrado'];
    }

    // Guardar los cambios en la base de datos
    $pedido->modificarPedido();

    $payload = json_encode(array("mensaje" => "Pedido modificado con éxito"));
    return $response->getBody()->write($payload);
}
public function BorrarUno($request, $response, $args)
{
    $id = $args['id'];

    // Verificar si el pedido existe
    $pedido = Pedido::obtenerPedido($id);

    if (!$pedido) {
        $payload = json_encode(array("mensaje" => "El pedido no existe"));
        return $response->withStatus(404)->getBody()->write($payload);
    }

    // Eliminar el pedido de la base de datos
    $pedido->eliminarPedido();

    $payload = json_encode(array("mensaje" => "Pedido eliminado con éxito"));
    return $response->getBody()->write($payload);
}

}
