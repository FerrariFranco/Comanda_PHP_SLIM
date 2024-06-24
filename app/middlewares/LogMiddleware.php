<?php

// namespace App\Middlewares;

// use Psr\Http\Message\ResponseInterface as Response;
// use Psr\Http\Message\ServerRequestInterface as Request;
// use Psr\Http\Server\MiddlewareInterface;
// use Psr\Http\Server\RequestHandlerInterface;
// use Slim\Psr7\Response as SlimResponse;
// use AccesoDatos;

// class LogMiddleware implements MiddlewareInterface
// {
//     public function process(Request $request, RequestHandlerInterface $handler): Response
//     {
//         $method = $request->getMethod();
//         $uri = (string) $request->getUri(); // Convertir URI a cadena
//         $dateTime = date('Y-m-d H:i:s');

//         $objAccesoDatos = AccesoDatos::obtenerInstancia();
//         $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO log_operaciones (metodo, uri, fecha_hora) VALUES (:metodo, :uri, :fecha_hora)");
//         $consulta->bindValue(':metodo', $method, \PDO::PARAM_STR);
//         $consulta->bindValue(':uri', $uri, \PDO::PARAM_STR);
//         $consulta->bindValue(':fecha_hora', $dateTime, \PDO::PARAM_STR);
//         $consulta->execute();

//         // Continuar con la solicitud
//         return $handler->handle($request);
//     }
// }
