<?php

namespace App\Util;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

/**
 * This is a wrapper to get current hasher method in application
 */
class HasherUtil
{
    public function __construct(
        #[Autowire('%hashing_method%')]
        private readonly string $hashingMethodName
    ) {
    }

    public function getPasswordHasher(): PasswordHasherInterface
    {
        return $this->createHasherFactory()->getPasswordHasher('common');
    }

    private function createHasherFactory(): PasswordHasherFactoryInterface
    {
        return new PasswordHasherFactory([
            'common' => ['algorithm' => $this->hashingMethodName]
        ]);
    }
}
