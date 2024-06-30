<?php
class PedidoProducto
{
    public $id;
    public $idPedido;
    public $idProducto;
    public $tiempoDePreparacion;
    public $momentoEntregado;
    public $estado;

    public function crearPedidoProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedido_productos (idPedido, idProducto) VALUES (:idPedido, :idProducto)");
        $consulta->bindValue(':idPedido', $this->idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':idProducto', $this->idProducto, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }
    public static function agregarProductoAPedido($idPedido, $idProducto)
    {
        $producto = Producto::obtenerProducto($idProducto);
        $precioProducto = $producto->precio;

        $pedidoProducto = new PedidoProducto();
        $pedidoProducto->idPedido = $idPedido;
        $pedidoProducto->idProducto = $idProducto;
        $idPedidoProducto = $pedidoProducto->crearPedidoProducto();

        Pedido::actualizarPrecioTotal($idPedido, $precioProducto);

        $pedido = Pedido::obtenerPedido($idPedido);
        if ($pedido) {
            $pedido->cargarProductos();
        }

        return $idPedidoProducto;
    }


    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, idPedido, idProducto, tiempoDePreparacion, momentoEntregado, estado FROM pedido_productos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'PedidoProducto');
    }

    public static function obtenerPedidoProducto($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, idPedido, idProducto, tiempoDePreparacion, momentoEntregado, estado FROM pedido_productos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('PedidoProducto');
    }

    public static function obtenerProductosPorPedido($idPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, idPedido, idProducto, tiempoDePreparacion, momentoEntregado, estado FROM pedido_productos WHERE idPedido = :idPedido");
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'PedidoProducto');
    }


    public static function PrepararProductoPedido($id, $tiempoDePreparacion){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE pedido_productos SET estado = 'EN PREPARACION', tiempoDePreparacion = :tiempoDePreparacion WHERE id = :id");
        $consulta->bindParam(':id', $id, PDO::PARAM_INT);
        $consulta->bindParam(':tiempoDePreparacion', $tiempoDePreparacion, PDO::PARAM_INT);
        $consulta->execute();
        

    }
    public static function ServirProductoPedido($id)
    {
        $estado = "SERVIDO";
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $momentoEntregado = date('Y-m-d H:i:s');
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE pedido_productos SET estado = :estado, momentoEntregado = :momentoEntregado WHERE id = :id");
        $consulta->bindParam(':id', $id, PDO::PARAM_INT);
        $consulta->bindParam(':estado', $estado, PDO::PARAM_STR);
        $consulta->bindParam(':momentoEntregado', $momentoEntregado, PDO::PARAM_STR);
        $consulta->execute();
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
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedido_productos SET eliminado = 1 WHERE id = :id");
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

    public static function obtenerProductosEntregadosPorPedido($idPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idProducto, momentoEntregado FROM pedido_productos WHERE idPedido = :idPedido AND momentoEntregado IS NOT NULL");
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function obtenerSectorResponsable($idProducto)
{
    $db = AccesoDatos::obtenerInstancia();
    
    $consulta = $db->prepararConsulta("SELECT idTipo FROM productos WHERE id = :idProducto");
    $consulta->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);
    $consulta->execute();
    $idTipo = $consulta->fetchColumn();

    if (!$idTipo) {
        throw new Exception("Tipo no encontrado para el id de producto dado");
    }

    $consulta = $db->prepararConsulta("SELECT idSector FROM tipos WHERE id = :idTipo");
    $consulta->bindValue(':idTipo', $idTipo, PDO::PARAM_INT);
    $consulta->execute();
    $idSector = $consulta->fetchColumn();

    if (!$idSector) {
        throw new Exception("Sector no encontrado para el id de tipo dado");
    }

    return $idSector;
}

public static function obtenerPedidosPorSector($idSector)
    {
        $db = AccesoDatos::obtenerInstancia();

        $consulta = $db->prepararConsulta("
            SELECT pp.*
            FROM pedido_productos pp
            JOIN productos p ON pp.idProducto = p.id
            JOIN tipos t ON p.idTipo = t.id
            WHERE t.idSector = :idSector AND pp.estado = 'PENDIENTE'
        ");
        $consulta->bindValue(':idSector', $idSector, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'PedidoProducto');
    }

    public static function obtenerPendientes()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, idPedido, idProducto, tiempoDePreparacion, estado FROM pedido_productos WHERE estado = 'PENDIENTE'");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'PedidoProducto');
    }
}
