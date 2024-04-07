<?php

namespace App\Tests\Integration\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\ResetDatabase;

class AuthControllerTest extends WebTestCase
{
    use ResetDatabase;

    private ?KernelBrowser $client = null;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testRegistrationSuccess(): void
    {
        $requestData = [
            'email' => 'test@email.com',
            'password' => 'password'
        ];

        $this->makeRegistrationRequest($requestData);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        // check response status code set well
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // check response structure is correct
        $this->assertArrayHasKey('errors', $responseData);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('code', $responseData);

        // check response data is correct
        $this->assertNull($responseData['errors']);
        $this->assertEquals(Response::HTTP_OK, $responseData['code']);
        $this->assertArrayHasKey('token', $responseData['message']);
        $this->assertNotEmpty($responseData['message']['token']);
    }

    /**
     * @dataProvider invalidEmailProvider
     */
    public function testRegistrationEmailValidationFail(string $email, string $expectedError): void
    {
        $requestData = [
            'email' => $email,
            'password' => 'password'
        ];

        $this->makeRegistrationRequest($requestData);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        // check response status code set well
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertNotEmpty($responseData);
        $this->assertArrayHasKey('errors', $responseData, 'Response hasn\'t an error key');
        $this->assertContains($expectedError, $responseData['errors']);
    }

    private function invalidEmailProvider(): \Generator
    {
        yield 'Invalid Email' => ['invalidemail', 'The email: "invalidemail" is not valid'];
        yield 'Blank Email' => ['', 'The email cannot be blank!'];
    }

    /**
     * @param mixed[] $requestData
     */
    protected function makeRegistrationRequest(array $requestData): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/user/auth/register',
            parameters: [],
            files: [],
            server: ['Content-Type' => 'application/json'],
            content: json_encode($requestData)
        );
    }
}
