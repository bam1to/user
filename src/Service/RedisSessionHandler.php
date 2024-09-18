<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\Storage\Handler\RedisSessionHandler as SessionHandler;

class RedisSessionHandler
{
    public function __construct(private SessionHandler $sessionHandler) {}

    public function destroy(string $sessionId): bool
    {
        try {
            if (!$this->sessionHandler->validateId($sessionId)) {
                return false;
            }

            return $this->sessionHandler->destroy($sessionId);
        } catch (\LogicException $e) {
            return false;
        }
    }
}
