<?php
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable
{
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
        $payload = json_encode(array("listaPedido" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $idUsuario = $parametros['idUsuario'];
        $idMesa = $parametros['idMesa'];
        $precioTotal = $parametros['precioTotal'];
        $cobrado = $parametros['cobrado'];
        $momentoCobrado = $parametros['momentoCobrado'];

        // Crear el pedido
        $pedido = new Pedido();
        $pedido->idUsuario = $idUsuario;
        $pedido->idMesa = $idMesa;
        $pedido->precioTotal = $precioTotal;
        $pedido->cobrado = $cobrado;
        $pedido->momentoCobrado = $momentoCobrado;
        $pedido->crearPedido();

        $payload = json_encode(array("mensaje" => "Pedido creado con éxito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $id = $args['id'];
        Pedido::borrarPedido($id);

        $payload = json_encode(array("mensaje" => "Pedido borrado con éxito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $idUsuario = $parametros['idUsuario'];
        $idMesa = $parametros['idMesa'];
        $precioTotal = $parametros['precioTotal'];
        $cobrado = $parametros['cobrado'];
        $momentoCobrado = $parametros['momentoCobrado'];

        Pedido::modificarPedido($id, $idUsuario, $idMesa, $precioTotal, $cobrado, $momentoCobrado);

        $payload = json_encode(array("mensaje" => "Pedido modificado con éxito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function AgregarProducto($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $idPedido = $parametros['idPedido'];
        $idProducto = $parametros['idProducto'];
        $tiempoDePreparacion = $parametros['tiempoDePreparacion'];

        // Crear un nuevo pedido producto
        $pedidoProducto = new PedidoProducto();
        $pedidoProducto->idPedido = $idPedido;
        $pedidoProducto->idProducto = $idProducto;
        $pedidoProducto->tiempoDePreparacion = $tiempoDePreparacion;
        $pedidoProducto->momentoEntregado = null;
        $pedidoProducto->crearPedidoProducto();

        $payload = json_encode(array("mensaje" => "Producto agregado al pedido con éxito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function EntregarPedido($request, $response, $args)
    {
        $idPedido = $args['id'];
        Pedido::entregarPedido($idPedido);

        $payload = json_encode(array("mensaje" => "Pedido entregado con éxito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
