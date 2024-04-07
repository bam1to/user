<?php

namespace App\Tests\Integration\Repository;

use App\Dto\Input\UserRegistrationDto;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class UserRepositoryTest extends KernelTestCase
{
    use ResetDatabase, Factories;

    private UserRepository $userRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->userRepository = $this->getContainer()->get(UserRepository::class);
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
}
