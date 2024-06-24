<?php
require_once './models/PedidoProducto.php';
require_once './interfaces/IApiUsable.php';

class PedidoProductoController extends PedidoProducto implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        echo"asd";
        $parametros = $request->getParsedBody();
        $idPedido = $parametros['idPedido'];
        $idProducto = $parametros['idProducto'];
        $tiempoDePreparacion = $parametros['tiempoDePreparacion'];
        $momentoEntregado = $parametros['momentoEntregado'];

        $pedidoProducto = new PedidoProducto();
        $pedidoProducto->idPedido = $idPedido;
        $pedidoProducto->idProducto = $idProducto;
        $pedidoProducto->tiempoDePreparacion = $tiempoDePreparacion;
        $pedidoProducto->momentoEntregado = $momentoEntregado;

        $pedidoProducto->crearPedidoProducto();
        $payload = json_encode(array("mensaje" => "PedidoProducto creado con éxito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $pedidoProducto = PedidoProducto::obtenerPedidoProducto($id);
        $payload = json_encode($pedidoProducto);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = PedidoProducto::obtenerTodos();
        $payload = json_encode(array("listaPedidoProductos" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $idPedido = $parametros['idPedido'];
        $idProducto = $parametros['idProducto'];
        $tiempoDePreparacion = $parametros['tiempoDePreparacion'];
        $momentoEntregado = $parametros['momentoEntregado'];

        PedidoProducto::modificarPedidoProducto($id, $idPedido, $idProducto, $tiempoDePreparacion, $momentoEntregado);
        $payload = json_encode(array("mensaje" => "PedidoProducto modificado con éxito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $id = $args['id'];
        PedidoProducto::borrarPedidoProducto($id);
        $payload = json_encode(array("mensaje" => "PedidoProducto borrado con éxito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
