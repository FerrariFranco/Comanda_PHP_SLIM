<?php
require_once "./db/AccesoDatos.php";

class Producto
{
    public $id;
    public $nombre;
    public $precio;
    public $tipo;
    public $veces_solicitado;


    public function crearProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos (nombre, precio, idTipo) VALUES (:nombre, :precio, :idTipo)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':idTipo', (int)$this->tipo, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, precio, idTipo, veces_solicitado FROM productos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function obtenerProducto($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, precio, idTipo FROM productos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function modificarProducto($id, $nombre = null, $precio = null, $tipo = null)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $setClause = [];
        $params = [':id' => $id];

        if ($nombre !== null) {
            $setClause[] = 'nombre = :nombre';
            $params[':nombre'] = $nombre;
        }

        if ($precio !== null) {
            $setClause[] = 'precio = :precio';
            $params[':precio'] = $precio;
        }

        if ($tipo !== null) {
            $setClause[] = 'idTipo = :idTipo';
            $params[':idTipo'] = $tipo;
        }

        $sql = 'UPDATE productos SET ' . implode(', ', $setClause) . ' WHERE id = :id';
        $consulta = $objAccesoDatos->prepararConsulta($sql);

        foreach ($params as $param => $value) {
            $consulta->bindValue($param, $value);
        }

        $consulta->execute();
    }

    public static function borrarProducto($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE productos SET eliminado = 1 WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }
    public static function solicitarProducto($id) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE productos SET veces_solicitado = veces_solicitado + 1 WHERE id = :id");
        $consulta->bindParam(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function obtenerProductoMasSolicitada()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, precio, idTipo, veces_solicitado FROM Productos ORDER BY veces_solicitado DESC LIMIT 1");
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }
}

