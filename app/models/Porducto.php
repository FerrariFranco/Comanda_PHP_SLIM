<?php
class Producto
{
    public $id;
    public $nombre;
    public $precio;
    public $preparado;
    public $tipo;

    const TIPOS_VALIDOS = ['bebida', 'comida', 'postre'];

    public function __construct($nombre, $precio, $preparado, $tipo)
    {
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->preparado = $preparado;
        $this->setTipo($tipo);
    }

    public function setTipo($tipo)
    {
        if (in_array($tipo, self::TIPOS_VALIDOS)) {
            $this->tipo = $tipo;
        } else {
            throw new Exception("Tipo de producto no válido");
        }
    }

    public function crearProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos (nombre, precio, preparado, tipo) VALUES (:nombre, :precio, :preparado, :tipo)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':preparado', $this->preparado, PDO::PARAM_BOOL);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, precio, preparado, tipo FROM productos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function obtenerProducto($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, precio, preparado, tipo FROM productos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function modificarProducto($id, $nombre = null, $precio = null, $preparado = null, $tipo = null)
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

        if ($preparado !== null) {
            $setClause[] = 'preparado = :preparado';
            $params[':preparado'] = $preparado;
        }

        if ($tipo !== null) {
            if (!in_array($tipo, self::TIPOS_VALIDOS)) {
                throw new Exception("Tipo de producto no válido");
            }
            $setClause[] = 'tipo = :tipo';
            $params[':tipo'] = $tipo;
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
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE productos SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}
