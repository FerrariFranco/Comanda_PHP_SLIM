<?php
require_once "./db/AccesoDatos.php";

class Usuario
{
    public $id;
    public $usuario;
    public $clave;
    public $rol;
    public $sector;
    public $fechaBaja;
    public $nombre;

    public function crearUsuario()
    {
        $idRol = $this->obtenerIdRol($this->rol);
        $idSector = $this->obtenerIdSector($this->sector);
        
        if ($idRol === null || $idSector === null) {
            return null;
        }

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (usuario, clave, rol, sector, nombre) VALUES (:usuario, :clave, :rol, :sector, :nombre)");
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->bindValue(':rol', $idRol, PDO::PARAM_INT);
        $consulta->bindValue(':sector', $idSector, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->execute();
        
        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario, clave, rol, sector, nombre, fechaBaja FROM usuarios");
        $consulta->execute();
        
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUsuario($usuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario, clave, rol, sector, nombre, fechaBaja FROM usuarios WHERE usuario = :usuario");
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->execute();
        
        return $consulta->fetchObject('Usuario');
    }

    public static function modificarUsuario($id, $usuario = null, $clave = null, $rol = null, $sector = null, $nombre = null)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $setClause = [];
        $params = [':id' => $id];
        
        if ($usuario !== null) {
            $setClause[] = 'usuario = :usuario';
            $params[':usuario'] = $usuario;
        }
        
        if ($clave !== null) {
            $setClause[] = 'clave = :clave';
            $params[':clave'] = password_hash($clave, PASSWORD_DEFAULT);
        }
        
        if ($rol !== null) {
            $setClause[] = 'rol = :rol';
            $params[':rol'] = $rol;
        }
        
        if ($sector !== null) {
            $setClause[] = 'sector = :sector';
            $params[':sector'] = $sector;
        }

        if ($nombre !== null) {
            $setClause[] = 'nombre = :nombre';
            $params[':nombre'] = $nombre;
        }
        
        $sql = 'UPDATE usuarios SET ' . implode(', ', $setClause) . ' WHERE id = :id';
        $consulta = $objAccesoDatos->prepararConsulta($sql);
        
        foreach ($params as $param => $value) {
            $consulta->bindValue($param, $value);
        }
        
        $consulta->execute();
    }

    public static function borrarUsuario($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }

    private function obtenerIdRol($nombreRol)
    {
        $roles = [
            'socio' => 1,
            'cervezero' => 2,
            'mozo' => 3,
            'cocinero' => 4,
            'bartender' => 5
        ];

        if (array_key_exists($nombreRol, $roles)) {
            return $roles[$nombreRol]; // Devolver el ID correspondiente
        } else {
            return null; // Devolver null si el nombre de rol no se encuentra en el mapeo
        }
    }

    private function obtenerIdSector($nombreSector)
    {
        $sectores = [
            'cocina' => 1,
            'patio trasero' => 2,
            'barra' => 3,
            'candybar' => 4
        ];
        
        // Verificar si el nombre de sector existe en el mapeo
        if (array_key_exists($nombreSector, $sectores)) {
            return $sectores[$nombreSector]; // Devolver el ID correspondiente
        } else {
            return null; // Devolver null si el nombre de sector no se encuentra en el mapeo
        }
    }
}

