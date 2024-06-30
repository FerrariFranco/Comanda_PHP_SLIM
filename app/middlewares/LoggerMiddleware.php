<?php

namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use App\Models\Log;

class LoggerMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $user = $request->getAttribute('usuario'); 
        $method = $request->getMethod();
        $body = (string) $request->getBody();
        
        Log::create([
            'request_user' => $user,
            'http_method' => $method,
            'request_content' => $body
        ]);

        $response = $handler->handle($request);
        return $response;
    }
}
