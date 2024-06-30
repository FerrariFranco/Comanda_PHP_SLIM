<?php

namespace App\Models;

use PDO;
use AccesoDatos;

class Log
{
    public static function create($data)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO logs (request_user, http_method, request_content) VALUES (:request_user, :http_method, :request_content)");
        $consulta->bindValue(':request_user', $data['usuario'], PDO::PARAM_INT);
        $consulta->bindValue(':http_method', $data['http_method'], PDO::PARAM_STR);
        $consulta->bindValue(':request_content', $data['request_content'], PDO::PARAM_STR);
        $consulta->execute();
    }
}
