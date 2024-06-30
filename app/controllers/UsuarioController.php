<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';
require_once './models/Pedido.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UsuarioController extends Usuario implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = (array)$request->getParsedBody();
        $usuario = $parametros['usuario'];
        
        $clave = $parametros['clave'];
        $sector = $parametros['sector']; 
        $rol = $parametros['rol']; 
        $nombre = $parametros['nombre'];
        
        $usr = new Usuario();
        $usr->usuario = $usuario;
        $usr->clave = $clave;
        $usr->sector = $sector; 
        $usr->rol = $rol; 
        $usr->nombre = $nombre;
    
        $usr->crearUsuario();
    
        $payload = json_encode(["mensaje" => "Usuario creado con éxito"]);
    
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $usr = $args['usuario'];
        $usuario = Usuario::obtenerUsuario($usr);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::obtenerTodos();
        $payload = json_encode(["listaUsuario" => $lista]);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = (array)$request->getParsedBody();

        $id = $parametros['id'] ?? null;
        $usuario = $parametros['usuario'] ?? null;
        echo $id;
        echo "asd";
        $clave = $parametros['clave'] ?? null;
        $rol = $parametros['rol'] ?? null;
        $sector = $parametros['sector'] ?? null;
        $nombre = $parametros['nombre'] ?? null;

        Usuario::modificarUsuario($id, $usuario, $clave, $rol, $sector, $nombre);

        $payload = json_encode(["mensaje" => "Usuario modificado con éxito"]);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $id = $args['id'];
        Usuario::borrarUsuario($id);

        $payload = json_encode(["mensaje" => "Usuario borrado con éxito"]);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    // public function generarToken(Request $request, Response $response): Response
    // {
    //     $params = (array)$request->getParsedBody();
    //     $usuario = $params['usuario'] ?? '';
    //     $clave = $params['clave'] ?? '';

    //     $objAccesoDatos = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDatos->prepararConsulta("SELECT rol FROM usuarios WHERE usuario = :usuario AND clave = :clave");
    //     $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
    //     $consulta->bindValue(':clave', $clave, PDO::PARAM_STR);
    //     $consulta->execute();
    //     $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

    //     if ($resultado) {
    //         $rol = $resultado['rol'];
    //         $datos = ['usuario' => $usuario, 'rol' => $rol];
    //         $token = AutentificadorJWT::CrearToken($datos);
    //         $payload = json_encode(['token' => $token]);
    //         $response->getBody()->write($payload);
    //         return $response->withHeader('Content-Type', 'application/json');
    //     } else {
    //         $response->getBody()->write(json_encode(['mensaje' => 'Credenciales inválidas']));
    //         return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    //     }

        
    // }
    public function ObtenerPedidosPorUsuario(Request $request, Response $response, $args): Response
    {
        $idUsuario = $args['id'];
        $pedidos = Pedido::obtenerPedidosPorUsuario($idUsuario);

        $payload = json_encode(["pedidos" => $pedidos]);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
