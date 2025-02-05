<?php

namespace App\Tests\Utils;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class JwtTokenProvider
{
    public static function getJwtToken(KernelBrowser $client, string $username, string $password): string
    {
        $client->jsonRequest(
            'POST',
            '/api/login_check',
            [
                'username' => $username,
                'password' => $password,
            ]
        );

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        return $data['token'] ?? '';
    }
}
