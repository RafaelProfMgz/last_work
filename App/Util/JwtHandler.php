<?php

namespace App\Util;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use UnexpectedValueException;

class JwtHandler
{
    public function encode(array $payload, string $secret, string $algo): string
    {
        return JWT::encode($payload, $secret, $algo);
    }

    public function decode(string $token, string $secret, string $algo): object
    {
        $key = new Key($secret, $algo);
        return JWT::decode($token, $key);
    }
}
