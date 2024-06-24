<?php


// use Psr\Http\Message\ResponseInterface as Response;
// use Psr\Http\Message\ServerRequestInterface as Request;
// use Psr\Http\Server\MiddlewareInterface;
// use Psr\Http\Server\RequestHandlerInterface;
// use App\Db\AccesoDatos;

// class CountBySectorAndEmployeeMiddleware
// {
//     public function __invoke(Request $request, Response $response, callable $next) {
//         // Contar la operación por sector y empleado
//         $sector = $request->getAttribute('sector'); // Asegúrate de que el atributo 'sector' esté definido
//         $empleado = $request->getAttribute('empleado'); // Asegúrate de que el atributo 'empleado' esté definido

//         if ($sector && $empleado) {
//             // Incrementar el contador en la base de datos
//             $objetoAccesoDato = AccesoDatos::obtenerInstancia();
//             $consulta = $objetoAccesoDato->prepararConsulta("UPDATE operaciones SET contador = contador + 1 WHERE sector = :sector AND empleado = :empleado");
//             $consulta->bindValue(':sector', $sector, \PDO::PARAM_STR);
//             $consulta->bindValue(':empleado', $empleado, \PDO::PARAM_STR);
//             $consulta->execute();
//         }

//         return $handler->handle($request);
//     }
// }
