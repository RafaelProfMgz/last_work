<?php

namespace Src\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Src\Util\JwtHandler;

class AuthMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $token = $request->getHeader('Authorization')[0] ?? '';

        if (!$token) {
            return $response->withJson(['error' => 'Token não fornecido'], 401);
        }

        try {
            $decoded = JwtHandler::decodeToken($token);
            $request = $request->withAttribute('user', $decoded);
        } catch (\Exception $e) {
            return $response->withJson(['error' => 'Token inválido ou expirado'], 401);
        }

        return $next($request, $response);
    }
}
