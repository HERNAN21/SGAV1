<?php

namespace App\Services;

class AuthService
{
    public function issueToken(array $credentials): array
    {
        return $credentials;
    }
}