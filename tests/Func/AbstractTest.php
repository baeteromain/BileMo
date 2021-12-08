<?php


namespace App\Tests\Func;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

abstract class AbstractTest extends ApiTestCase
{
    private $token;
    private $clientWithCredentials;


    public function setUp(): void
    {
        self::bootKernel();
    }

    protected function createClientWithCredentials($token = null): Client
    {
        $token = $token ?: $this->getToken();

        return static::createClient([], ['headers' => ['authorization' => 'Bearer ' . $token]]);
    }

    /**
     * Use other credentials if needed.
     */
    protected function getToken($body = []): string
    {
        if ($this->token) {
            return $this->token;
        }

        $response = static::createClient()->request('POST', '/api/login', ['json' => $body ?: [
            'email' => 'api@bilemo.com',
            'password' => 'adminadmin',
        ], 'headers' => ['Content-Type' => 'application/json']]);

        $this->token = "";
        $this->assertResponseIsSuccessful();

        $data = json_decode($response->getContent());
        $this->token = $data->token;

        return $data->token;
    }
}