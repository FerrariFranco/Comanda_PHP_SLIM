<?php
require_once "Producto.php";
class Pedido
{
    public $id;
    public $idMesa;
    public $listaProductos; // This will be an array of Product objects
    public $precioTotal;
    public $cobrado;

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (idMesa, precioTotal, cobrado) VALUES (:idMesa, :precioTotal, :cobrado)");
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':precioTotal', $this->precioTotal, PDO::PARAM_STR);
        $consulta->bindValue(':cobrado', $this->cobrado, PDO::PARAM_BOOL);
        $consulta->execute();
        $this->id = $objAccesoDatos->obtenerUltimoId();

        foreach ($this->listaProductos as $producto) {
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos_pedidos (idPedido, idProducto) VALUES (:idPedido, :idProducto)");
            $consulta->bindValue(':idPedido', $this->id, PDO::PARAM_INT);
            $consulta->bindValue(':idProducto', $producto->id, PDO::PARAM_INT);
            $consulta->execute();
        }
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos");
        $consulta->execute();
        $pedidos = $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');

        foreach ($pedidos as $pedido) {
            $pedido->listaProductos = self::obtenerProductosPorPedido($pedido->id);
        }

        return $pedidos;
    }

    public static function obtenerPedido($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
        $pedido = $consulta->fetchObject('Pedido');
        if ($pedido) {
            $pedido->listaProductos = self::obtenerProductosPorPedido($pedido->id);
        }
        return $pedido;
    }

    public static function obtenerProductosPorPedido($idPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT p.* FROM productos p INNER JOIN productos_pedidos pp ON p.id = pp.idProducto WHERE pp.idPedido = :idPedido");
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public function eliminarPedido()
{
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $consulta = $objAccesoDatos->prepararConsulta("DELETE FROM pedidos WHERE id = :id");
    $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
    $consulta->execute();
}
}
