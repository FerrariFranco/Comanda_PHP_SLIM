<?php
class Tipo
{
    public $id;
    public $tipo;
    public $idSector;

    public function __construct($tipo, $idSector)
    {
        $this->tipo = $tipo;
        $this->idSector = $idSector;
    }

    public function crearTipo()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO tipos (tipo, idSector) VALUES (:tipo, :idSector)");
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':idSector', $this->idSector, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, tipo, idSector FROM tipos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Tipo');
    }

    public static function obtenerTipo($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, tipo, idSector FROM tipos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Tipo');
    }

    public static function modificarTipo($id, $tipo = null, $idSector = null)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $setClause = [];
        $params = [':id' => $id];

        if ($tipo !== null) {
            $setClause[] = 'tipo = :tipo';
            $params[':tipo'] = $tipo;
        }

        if ($idSector !== null) {
            $setClause[] = 'idSector = :idSector';
            $params[':idSector'] = $idSector;
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
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("DELETE FROM tipos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }
}
