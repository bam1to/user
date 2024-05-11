<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use App\Util\HasherUtil;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function __construct(private readonly HasherUtil $hasherUtil)
    {
    }

    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne([
            'email' => 'w1@test.com',
            'password' => $this->hasherUtil->getPasswordHasher()->hash('qwe123')
        ]);

        $manager->flush();
    }
}
