<?php
class rol
{
    public $id;
    public $rol;

    public function __construct($rol)
    {
        $this->rol = $rol;
    }

    public function crearRol()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO roles (rol) VALUES (:rol)");
        $consulta->bindValue(':rol', $this->rol, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, rol FROM roles");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'rol');
    }

    public static function obtenerRol($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, rol FROM roles WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('rol');
    }

    public static function modificarRol($id, $rol = null)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $setClause = [];
        $params = [':id' => $id];

        if ($rol !== null) {
            $setClause[] = 'rol = :rol';
            $params[':rol'] = $rol;
        }

        $sql = 'UPDATE roles SET ' . implode(', ', $setClause) . ' WHERE id = :id';
        $consulta = $objAccesoDatos->prepararConsulta($sql);

        foreach ($params as $param => $value) {
            $consulta->bindValue($param, $value);
        }

        $consulta->execute();
    }

    public static function borrarRol($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE roles SET eliminado = 1 WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }
}
