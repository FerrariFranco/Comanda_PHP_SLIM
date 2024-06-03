<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario implements IApiUsable
{
  public function CargarUno( $request, $response, $args)
  {
      $parametros = $request->getParsedBody();
  
      $usuario = $parametros['usuario'];
      $clave = $parametros['clave'];
      $sector = $parametros['sector']; 
      $rol = $parametros['rol']; 
  
      $sectoresValidos = ['cocina', 'patio trasero', 'barra', 'candybar']; 
      $rolesValidos = ['socio', 'cervezero', 'mozo', 'cocinero', 'bartender']; 
      if (!in_array($sector, $sectoresValidos) || !in_array($rol, $rolesValidos)) {
          $payload = json_encode(array("mensaje" => "El sector o el rol no son válidos"));
          return $response->withStatus(400)->getBody()->write($payload);
      }
  
      $usr = new Usuario();
      $usr->usuario = $usuario;
      $usr->clave = $clave;
      $usr->sector = $sector; 
      $usr->rol = $rol; 
  
      $usr->crearUsuario();
  
      $payload = json_encode(array("mensaje" => "Usuario creado con éxito"));
  
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
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::obtenerTodos();
        $payload = json_encode(array("listaUsuario" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];
        $nombre = $parametros['nombre'];
        Usuario::modificarUsuario($id,$nombre);

        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuarioId = $parametros['usuarioId'];
        Usuario::borrarUsuario($usuarioId);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
