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
        
        

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (usuario, clave, rol, sector, nombre) VALUES (:usuario, :clave, :rol, :sector, :nombre)");
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave);
        $consulta->bindValue(':rol', $this->rol, PDO::PARAM_INT);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_INT);
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
    public static function modificarUsuario($id, $usuario, $clave, $rol, $sector, $nombre)
{
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $sql = 'UPDATE usuarios SET usuario = :usuario, clave = :clave, rol = :rol, sector = :sector, nombre = :nombre WHERE id = :id';
    $consulta = $objAccesoDatos->prepararConsulta($sql);

    $consulta->bindValue(':id', $id, PDO::PARAM_INT);
    $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
    $consulta->bindValue(':clave', $clave, PDO::PARAM_STR);
    $consulta->bindValue(':rol', $rol, PDO::PARAM_STR);
    $consulta->bindValue(':sector', $sector, PDO::PARAM_STR);
    $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);

    $consulta->execute();
}


public static function borrarUsuario($id)
{
    $objAccesoDato = AccesoDatos::obtenerInstancia();
    $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET eliminado = 1 WHERE id = :id");
    $consulta->bindValue(':id', $id, PDO::PARAM_INT);
    $consulta->execute();
}

    public static function obtenerUsuarioPorCredenciales($usuario, $clave)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario, clave, rol, sector, nombre, fechaBaja FROM usuarios WHERE usuario = :usuario AND clave = :clave");
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $clave, PDO::PARAM_STR); 
        $consulta->execute();
        
        return $consulta->fetchObject('Usuario');
    }

}

