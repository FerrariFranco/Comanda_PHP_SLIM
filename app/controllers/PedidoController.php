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
        //$precioTotal = $parametros['precioTotal'];
        //$cobrado = $parametros['cobrado'];
        //$momentoCobrado = $parametros['momentoCobrado'];
        $pedido = new Pedido();
        $pedido->idUsuario = $idUsuario;
        $pedido->idMesa = $idMesa;
        //$pedido->precioTotal = $precioTotal;
        //$pedido->cobrado = false;
        //$pedido->momentoCobrado = 0;
        $pedido->crearPedido();

        $payload = json_encode(array("mensaje" => "Pedido creado con éxito"));
        Mesa::solicitarMesa($idMesa);
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
        echo"asd";
        $parametros = $request->getParsedBody();
        $idPedido = $parametros['idPedido'];
        $idProducto = $parametros['idProducto'];
        PedidoProducto::agregarProductoAPedido($idPedido, $idProducto);
        $payload = json_encode(array("mensaje" => "Producto agregado al pedido con éxito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function EntregaPedido($request, $response, $args)
    {
        $idPedido = $args['id'];
        Pedido::entregarPedido($idPedido);

        $payload = json_encode(array("mensaje" => "Pedido entregado con éxito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function obtenerEstado($request, $response, $args)
    {
        $idPedido = $args['idPedido'];
        $estadoPedido = Pedido::obtenerEstadoPedido($idPedido);

        if ($estadoPedido) {
            $payload = json_encode($estadoPedido);
        } else {
            $payload = json_encode(['mensaje' => 'Pedido no encontrado']);
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }


    public function guardarLaImagen($request, $response, $args)
{
    
    $parametros = $request->getParsedBody();
    $idPedido = $parametros['idPedido'];
    $uploadedFiles = $request->getUploadedFiles();


    if (isset($uploadedFiles['imagen'])) {
        $imagen = $_FILES['imagen'];

        if (isset($imagen)) {
            try {
                Pedido::GuardarImagen($idPedido, $imagen);
                $payload = json_encode(array("mensaje" => "Imagen guardada correctamente"));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            } catch (Exception $e) {
                $payload = json_encode(array("error" => $e->getMessage()));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
            }
        } else {
            $payload = json_encode(array("error" => "Error al subir la imagen"));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    } else {
        $payload = json_encode(array("error" => "No se encontró ninguna imagen para subir"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
}

public function cobrarPedido($request, $response, $args)
    {
        $idPedido = $args['id'];

        try {
            $resultado = Pedido::cobrar($idPedido);
            if ($resultado) {
                $payload = json_encode(array("mensaje" => "Pedido cobrado exitosamente. Gracias por comer en EL GORDO RESTORAN, puede dejarnos una review con la funcion encuesta!"));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            } else {
                $payload = json_encode(array("error" => "Error al cobrar el pedido. Pedido no encontrado."));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
            }
        } catch (Exception $e) {
            $payload = json_encode(array("error" => $e->getMessage()));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
}
public function ProductosConTiempo($request, $response, $args)
{
    $idPedido = $args['id'];
    $productosConTiempo = Pedido::obtenerProductosConTiempo($idPedido);

    if ($productosConTiempo) {
        $response->getBody()->write(json_encode($productosConTiempo));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        $response->getBody()->write(json_encode(['error' => 'No se encontraron productos para el pedido.']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
}
}