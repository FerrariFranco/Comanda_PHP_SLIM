<?php

use Firebase\JWT\JWT;
require_once "./db/accesodatos.php";

class AutentificadorJWT
{
    private static $claveSecreta = "KASSADIN";
    private static $tipoEncriptacion = ['HS256'];
    public static function CrearToken($datos)
    {
        $ahora = time();
        $payload = array(
            'iat' => $ahora,
            'exp' => $ahora + (600000000),
            'aud' => self::Aud(),
            'data' => $datos,
            'app' => "Comanda"
        );
        $token = JWT::encode($payload, self::$claveSecreta);
        
        self::GuardarTokenEnDB($datos['usuario'], $datos['rol'], $token, $ahora, $ahora + (600000000));

        return $token;
    }

    public static function VerificarToken($token)
    {
        if (empty($token)) {
            throw new Exception("El token está vacío.");
        }
        try {
            $decodificado = JWT::decode(
                $token,
                self::$claveSecreta,
                self::$tipoEncriptacion
            );
        } catch (Exception $e) {
            throw $e;
        }
        if ($decodificado->aud !== self::Aud()) {
            throw new Exception("No es el usuario válido");
        }
    }

    public static function ObtenerPayLoad($token)
    {
        if (empty($token)) {
            throw new Exception("El token está vacío.");
        }
        return JWT::decode(
            $token,
            self::$claveSecreta,
            self::$tipoEncriptacion
        );
    }

    public static function ObtenerData($token)
    {
        return JWT::decode(
            $token,
            self::$claveSecreta,
            self::$tipoEncriptacion
        )->data;
    }

    private static function Aud()
    {
        $aud = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $aud = $_SERVER['REMOTE_ADDR'];
        }

        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();

        return sha1($aud);
    }

    private static function GuardarTokenEnDB($usuario, $rol, $token,$creado_en, $vence_el)
    {
        
        $db = AccesoDatos::obtenerInstancia();
        $consulta = $db->prepararConsulta("INSERT INTO tokens (usuario, rol, token , creado_en, vence_el) VALUES (:usuario, :rol, :token, :creado_en, :vence_el)");
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->bindValue(':rol', $rol, PDO::PARAM_STR);
        $consulta->bindValue(':creado_en', $creado_en, PDO::PARAM_STR);
        $consulta->bindValue(':vence_el', $vence_el, PDO::PARAM_STR);
        $consulta->bindValue(':token', $token, PDO::PARAM_STR); 
        $consulta->execute();
    }
}
