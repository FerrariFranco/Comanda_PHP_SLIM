<?php
require_once "./db/AccesoDatos.php";

class Tipo
{
    public $id;
    public $tipo;
    public $idsector;

    public function __construct() {}

    public function crearTipo()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO tipos (tipo, idsector) VALUES (:tipo, :idsector)");
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':idsector', $this->idsector, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, tipo, idsector FROM tipos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Tipo');
    }

    public static function obtenerTipo($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, tipo, idsector FROM tipos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Tipo');
    }

    public static function modificarTipo($id, $tipo = null, $idsector = null)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $setClause = [];
        $params = [':id' => $id];

        if ($tipo !== null) {
            $setClause[] = 'tipo = :tipo';
            $params[':tipo'] = $tipo;
        }

        if ($idsector !== null) {
            $setClause[] = 'idsector = :idsector';
            $params[':idsector'] = $idsector;
        }

        $sql = 'UPDATE tipos SET ' . implode(', ', $setClause) . ' WHERE id = :id';
        $consulta = $objAccesoDatos->prepararConsulta($sql);

        foreach ($params as $param => $value) {
            $consulta->bindValue($param, $value);
        }

        $consulta->execute();
    }

    public static function borrarTipo($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE tipos SET eliminado = 1 WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }
}
