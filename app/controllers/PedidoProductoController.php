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
        //$tiempoDePreparacion = $parametros['tiempoDePreparacion'];
        //$momentoEntregado = $parametros['momentoEntregado'];

        $pedidoProducto = new PedidoProducto();
        $pedidoProducto->idPedido = $idPedido;
        $pedidoProducto->idProducto = $idProducto;
        //$pedidoProducto->tiempoDePreparacion = $tiempoDePreparacion;
        ///$pedidoProducto->momentoEntregado = $momentoEntregado;

        $pedidoProducto->crearPedidoProducto();
        $payload = json_encode(array("mensaje" => "PedidoProducto creado con éxito"));
        Producto::solicitarProducto($parametros["idProducto"]);
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

    public function obtenerPedidosPendientes($request, $response, $args)
    {
        try {
            $pendientes = PedidoProducto::obtenerPendientes();
            $payload = json_encode(array("pendientes" => $pendientes));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (Exception $e) {
            $payload = json_encode(array("error" => $e->getMessage()));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function Servir($request, $response, $args)
    {
        $id = $args['id'];
        PedidoProducto::ServirProductoPedido($id);
        $payload = json_encode("PRODUCTO SERVIDO");

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function Preparar($request, $response, $args)
    {
        $parsedBody = $request->getParsedBody();
        $id = $parsedBody['id'];
        $tiempoDePreparacion = $parsedBody['tiempoDePreparacion'];
        PedidoProducto::PrepararProductoPedido($id, $tiempoDePreparacion);
        $payload = json_encode("PRODUCTO EN PREPARACION");

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerPedidosPorSector($request, $response, $args)
    {
        $idSector = $args['id'];

        try {
            $pedidos = PedidoProducto::obtenerPedidosPorSector($idSector);
            $payload = json_encode($pedidos);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (Exception $e) {
            $payload = json_encode(array("error" => $e->getMessage()));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function TraerPendientes($request, $response, $args)
    {
        try {
            $pedidosPendientes = PedidoProducto::obtenerPendientes();
            $payload = json_encode($pedidosPendientes);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (Exception $e) {
            $payload = json_encode(array("error" => $e->getMessage()));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

}
