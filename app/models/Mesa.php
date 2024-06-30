<?php

require_once "./db/accesodatos.php";
class Mesa
{
    public $id;
    public $IdSector;
    public $capacidad;

    public function __construct()
    {


    }

    public function crearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (IdSector, capacidad) VALUES (:IdSector, :capacidad)");
        $consulta->bindValue(':IdSector', $this->IdSector, PDO::PARAM_INT);
        $consulta->bindValue(':capacidad', $this->capacidad, PDO::PARAM_INT);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodas()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, IdSector, capacidad, estadoMesa, veces_solicitada FROM mesas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function obtenerMesa($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, IdSector, capacidad, estadoMesa FROM mesas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }

    public static function modificarMesa($id, $IdSector = null, $capacidad = null, $atendida = null, $estadoMesa = null)
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

        if ($estadoMesa !== null) {
            $setClause[] = 'estadoMesa = :estadoMesa';
            $params[':estadoMesa'] = $estadoMesa;
        }

        $sql = 'UPDATE mesas SET ' . implode(', ', $setClause) . ' WHERE id = :id';
        $consulta = $objAccesoDatos->prepararConsulta($sql);

        foreach ($params as $param => $value) {
            $consulta->bindValue($param, $value);
        }

        $consulta->execute();
    }

    public static function borrarmesa($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET eliminado = 1 WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function cerrarMesa($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE mesas SET estadoMesa = 'cerrada' WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function CambiarEstado($id, $estadoMesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE mesas SET estadoMesa = :estadoMesa WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }


    public static function solicitarMesa($id) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE mesas SET veces_solicitada = veces_solicitada + 1, estadoMesa = 'ATENDIDA' WHERE id = :id");
        $consulta->bindParam(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function obtenerMesaMasSolicitada()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, IdSector, capacidad, estadoMesa, veces_solicitada FROM mesas ORDER BY veces_solicitada DESC LIMIT 1");
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }
}
