<?php
class PedidoProducto
{
    public $id;
    public $idPedido;
    public $idProducto;
    public $tiempoDePreparacion;
    public $momentoEntregado;

    public function crearPedidoProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedido_productos (idPedido, idProducto, tiempoDePreparacion, momentoEntregado) VALUES (:idPedido, :idProducto, :tiempoDePreparacion, :momentoEntregado)");
        $consulta->bindValue(':idPedido', $this->idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':idProducto', $this->idProducto, PDO::PARAM_INT);
        $consulta->bindValue(':tiempoDePreparacion', $this->tiempoDePreparacion, PDO::PARAM_INT);
        $consulta->bindValue(':momentoEntregado', $this->momentoEntregado, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }
    public static function agregarProductoAPedido($idPedido, $idProducto, $tiempoDePreparacion, $momentoEntregado)
    {
        $pedidoProducto = new PedidoProducto();
        $pedidoProducto->idPedido = $idPedido;
        $pedidoProducto->idProducto = $idProducto;
        $pedidoProducto->tiempoDePreparacion = $tiempoDePreparacion;
        $pedidoProducto->momentoEntregado = $momentoEntregado;

        $idPedidoProducto = $pedidoProducto->crearPedidoProducto();

        $pedido = Pedido::obtenerPedido($idPedido);
        $pedido->cargarProductos();

        return $idPedidoProducto;
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, idPedido, idProducto, tiempoDePreparacion, momentoEntregado FROM pedido_productos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'PedidoProducto');
    }

    public static function obtenerPedidoProducto($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, idPedido, idProducto, tiempoDePreparacion, momentoEntregado FROM pedido_productos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('PedidoProducto');
    }

    public static function obtenerProductosPorPedido($idPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, idPedido, idProducto, tiempoDePreparacion, momentoEntregado FROM pedido_productos WHERE idPedido = :idPedido");
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'PedidoProducto');
    }

    public static function modificarPedidoProducto($id, $idPedido, $idProducto, $tiempoDePreparacion, $momentoEntregado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE pedido_productos SET idPedido = :idPedido, idProducto = :idProducto, tiempoDePreparacion = :tiempoDePreparacion, momentoEntregado = :momentoEntregado WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);
        $consulta->bindValue(':tiempoDePreparacion', $tiempoDePreparacion, PDO::PARAM_INT);
        $consulta->bindValue(':momentoEntregado', $momentoEntregado, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function borrarPedidoProducto($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("DELETE FROM pedido_productos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function entregarProductosPorPedido($idPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $momentoEntregado = date('Y-m-d H:i:s');
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE pedido_productos SET momentoEntregado = :momentoEntregado WHERE idPedido = :idPedido");
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':momentoEntregado', $momentoEntregado, PDO::PARAM_STR);
        $consulta->execute();
    }
}
