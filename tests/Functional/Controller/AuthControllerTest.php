<?php

namespace App\Tests\Integration\Controller;

use App\DataFixtures\AppFixtures;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\ResetDatabase;

class AuthControllerTest extends WebTestCase
{
    use ResetDatabase;

    private ?KernelBrowser $client = null;
    private ?AbstractDatabaseTool $databaseTool = null;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
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
        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        // check response data is correct
        $this->assertNull($responseData['errors']);
        $this->assertEquals(Response::HTTP_CREATED, $responseData['code']);
        $this->assertArrayHasKey('session_id', $responseData['message']);
        $this->assertNotEmpty($responseData['message']['session_id']);
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

    public function testLoginSuccess()
    {
        $this->databaseTool->loadFixtures([AppFixtures::class]);

        $requestData = [
            'email' => 'w1@test.com',
            'password' => 'qwe123'
        ];

        $this->client->request(
            method: 'POST',
            uri: '/user/auth/login',
            parameters: [],
            files: [],
            server: ['Content-Type' => 'application/json'],
            content: json_encode($requestData)
        );

        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        // check response status code set well
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // check response data is correct
        $this->assertNull($responseData['errors']);
        $this->assertEquals(Response::HTTP_OK, $responseData['code']);
        $this->assertArrayHasKey('session_id', $responseData['message']);
        $this->assertNotEmpty($responseData['message']['session_id']);
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
