<?php

namespace App\Tests\Integration\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\ResetDatabase;

class AuthControllerTest extends WebTestCase
{
    use ResetDatabase;

    public function testRegistrationSuccess(): void
    {
        $client = self::createClient();

        $requestData = [
            'email' => 'test@email.com',
            'password' => 'password'
        ];

        $client->request(
            method: 'POST',
            uri: '/user/auth/register',
            parameters: [],
            files: [],
            server: ['Content-Type' => 'application/json'],
            content: json_encode($requestData)
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $responseData);
        $this->assertNotEmpty($responseData['token']);
    }

    public function testRegistrationValidationFail(): void
    {
        $client = static::createClient();

        $requestData = [
            'email' => 'invalidmail',
            'password' => 'password'
        ];

        $client->request(
            method: 'POST',
            uri: '/user/auth/register',
            parameters: [],
            files: [],
            server: ['Content-Type' => 'application/json'],
            content: json_encode($requestData)
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertNotEmpty($responseData);
    }
}
