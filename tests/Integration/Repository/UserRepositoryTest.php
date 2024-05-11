<?php

namespace App\Tests\Integration\Repository;

use App\DataFixtures\AppFixtures;
use App\Dto\Input\UserRegistrationDto;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class UserRepositoryTest extends KernelTestCase
{
    use ResetDatabase, Factories;

    private UserRepository $userRepository;
    private AbstractDatabaseTool $databaseTool;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->userRepository = $this->getContainer()->get(UserRepository::class);
        $this->databaseTool = $this->getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function testUserRegistrationSuccessful(): void
    {
        $user = new UserRegistrationDto();

        $user->setEmail('test@email.com');
        $user->setPassword('testPassword');
        $user->setPhone('+48123456789');

        $insertedUser = $this->userRepository->registerUser($user);

        $this->assertNotNull($insertedUser);
        $this->assertEquals(
            $insertedUser->getId(),
            $this->userRepository->findOneBy(['id' => $insertedUser->getId()])->getId()
        );
    }

    public function testUserRegistrationUniqEmailValidationFailed(): void
    {
        $this->databaseTool->loadFixtures([AppFixtures::class]);
    }
}
