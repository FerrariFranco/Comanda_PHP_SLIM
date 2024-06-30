<?php
require_once './models/PedidoProducto.php';

class Pedido
{
    public $id;
    public $idUsuario;
    public $idMesa;
    public $precioTotal;
    public $cobrado;
    public $momentoCobrado;
    public $productos;
    public $momentoPedido;

    public $RutaImagen;

    public function crearPedido()
    {
        $this->momentoPedido = date('Y-m-d H:i:s');
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (idUsuario, idMesa, momentoPedido) VALUES (:idUsuario, :idMesa, :momentoPedido)");
        $consulta->bindValue(':idUsuario', $this->idUsuario, PDO::PARAM_INT);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':momentoPedido', $this->momentoPedido, PDO::PARAM_INT);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, idUsuario, idMesa, precioTotal, cobrado, momentoCobrado, momentoPedido, RutaImagen FROM pedidos");
        $consulta->execute();

        $pedidos = $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
        foreach ($pedidos as $pedido) {
            $pedido->cargarProductos();
        }

        return $pedidos;
    }

    public static function obtenerPedido($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, idUsuario, idMesa, precioTotal, cobrado, momentoCobrado FROM pedidos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        $pedido = $consulta->fetchObject('Pedido');
        $pedido->cargarProductos();

        return $pedido;
    }

    public static function modificarPedido($id, $idUsuario, $idMesa, $precioTotal, $cobrado, $momentoCobrado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE pedidos SET idUsuario = :idUsuario, idMesa = :idMesa, precioTotal = :precioTotal, cobrado = :cobrado, momentoCobrado = :momentoCobrado WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':precioTotal', $precioTotal, PDO::PARAM_STR);
        $consulta->bindValue(':cobrado', $cobrado, PDO::PARAM_BOOL);
        $consulta->bindValue(':momentoCobrado', $momentoCobrado, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function borrarPedido($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET eliminado = 1 WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function entregarPedido($idPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE pedido_productos SET momentoEntregado = :momentoEntregado WHERE idPedido = :idPedido");
        $fechaActual = date('Y-m-d H:i:s');
        $consulta->bindValue(':momentoEntregado', $fechaActual, PDO::PARAM_STR);
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->execute();
    }

    public function cargarProductos()
    {
        $this->productos = PedidoProducto::obtenerProductosPorPedido($this->id);
    }
    public static function obtenerPedidosPorUsuario($idUsuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, idUsuario, idMesa, precioTotal, cobrado, momentoCobrado FROM pedidos WHERE idUsuario = :idUsuario");
        $consulta->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $consulta->execute();

        $pedidos = $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
        foreach ($pedidos as $pedido) {
            $pedido->cargarProductos();
        }

        return $pedidos;
    }
    

    public static function actualizarPrecioTotal($idPedido, $precioProducto)
    {echo "entre";
        $pedido = self::obtenerPedido($idPedido);
        $nuevoPrecioTotal = $pedido->precioTotal + $precioProducto;

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE pedidos SET precioTotal = :precioTotal WHERE id = :id");
        $consulta->bindValue(':id', $idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':precioTotal', $nuevoPrecioTotal, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function obtenerEstadoPedido($idPedido)
    {
        $pedido = self::obtenerPedido($idPedido);
        if (!$pedido) {
            return null;
        }

        $productosEntregados = PedidoProducto::obtenerProductosEntregadosPorPedido($idPedido);

        $estadoPedido = [
            'idPedido' => $pedido->id,
            'idUsuario' => $pedido->idUsuario,
            'idMesa' => $pedido->idMesa,
            'precioTotal' => $pedido->precioTotal,
            'cobrado' => $pedido->cobrado,
            'momentoCobrado' => $pedido->momentoCobrado,
            'momentoPedido' => $pedido->momentoPedido,
            'productosEntregados' => $productosEntregados
        ];

        return $estadoPedido;
    }

    public static function GuardarImagen($idPedido, $imagen)
{
    $targetDir = "./Fotos/2024/";
    
    $targetFilePath = $targetDir . "_". $idPedido . ".jpg";
    echo $targetFilePath;
    echo $imagen["tmp_name"];

    
        if (move_uploaded_file($imagen["tmp_name"], $targetFilePath)) {
            $rutaImagen = $targetFilePath;

            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("UPDATE pedidos SET RutaImagen = :RutaImagen WHERE id = :idPedido");
            $consulta->bindValue(':RutaImagen', $rutaImagen, PDO::PARAM_STR);
            $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
            $consulta->execute();

            return true;
        }
    }

    public static function cobrar($idPedido)
{
    $pedido = self::obtenerPedido($idPedido);
    if (!$pedido) {
        return false; 
    }



    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $consulta = $objAccesoDatos->prepararConsulta("UPDATE pedidos SET cobrado = 1, momentoCobrado = :momentoCobrado WHERE id = :idPedido");
    $consulta->bindValue(':momentoCobrado', date('Y-m-d H:i:s'), PDO::PARAM_STR);
    $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
    $consulta->execute();

    $consultaProductos = $objAccesoDatos->prepararConsulta("UPDATE pedido_productos SET estado = 'COBRADO' WHERE idPedido = :idPedido");
    $consultaProductos->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
    $consultaProductos->execute();

    $consultaMesa = $objAccesoDatos->prepararConsulta("UPDATE mesas SET estadoMesa = 'DISPONIBLE' WHERE id = :idMesa");
    $consultaMesa->bindValue(':idMesa', $pedido->idMesa, PDO::PARAM_INT);
    $consultaMesa->execute();

    return true;
}
public static function obtenerProductosConTiempo($idPedido)
{
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $consulta = $objAccesoDatos->prepararConsulta("
        SELECT nombreProducto, tiempoDePreparacion 
        FROM pedido_productos 
        WHERE idPedido = :idPedido
    ");
    $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
    $consulta->execute();

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}
}


