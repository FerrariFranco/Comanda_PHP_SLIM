<?php
class Mesa
{
    public $id;
    public $IdSector;
    public $capacidad;
    public $atendida;

    public function __construct($IdSector, $capacidad, $atendida)
    {
        $this->IdSector = $IdSector;
        $this->capacidad = $capacidad;
        $this->atendida = $atendida;
    }

    public function crearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (IdSector, capacidad, atendida) VALUES (:IdSector, :capacidad, :atendida)");
        $consulta->bindValue(':IdSector', $this->IdSector, PDO::PARAM_INT);
        $consulta->bindValue(':capacidad', $this->capacidad, PDO::PARAM_INT);
        $consulta->bindValue(':atendida', $this->atendida, PDO::PARAM_BOOL);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodas()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, IdSector, capacidad, atendida FROM mesas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function obtenerMesa($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, IdSector, capacidad, atendida FROM mesas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }

    public static function modificarMesa($id, $IdSector = null, $capacidad = null, $atendida = null)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $setClause = [];
        $params = [':id' => $id];

        if ($IdSector !== null) {
            $setClause[] = 'IdSector = :IdSector';
            $params[':IdSector'] = $IdSector;
        }

        if ($capacidad !== null) {
            $setClause[] = 'capacidad = :capacidad';
            $params[':capacidad'] = $capacidad;
        }

        if ($atendida !== null) {
            $setClause[] = 'atendida = :atendida';
            $params[':atendida'] = $atendida;
        }

        $sql = 'UPDATE mesas SET ' . implode(', ', $setClause) . ' WHERE id = :id';
        $consulta = $objAccesoDatos->prepararConsulta($sql);

        foreach ($params as $param => $value) {
            $consulta->bindValue($param, $value);
        }

        $consulta->execute();
    }

    public static function borrarMesa($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("DELETE FROM mesas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }
}
