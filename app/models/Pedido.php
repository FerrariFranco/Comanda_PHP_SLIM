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

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (idUsuario, idMesa, precioTotal, cobrado, momentoCobrado) VALUES (:idUsuario, :idMesa, :precioTotal, :cobrado, :momentoCobrado)");
        $consulta->bindValue(':idUsuario', $this->idUsuario, PDO::PARAM_INT);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':precioTotal', $this->precioTotal, PDO::PARAM_STR);
        $consulta->bindValue(':cobrado', $this->cobrado, PDO::PARAM_BOOL);
        $consulta->bindValue(':momentoCobrado', $this->momentoCobrado, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, idUsuario, idMesa, precioTotal, cobrado, momentoCobrado FROM pedidos");
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
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("DELETE FROM pedidos WHERE id = :id");
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
}

