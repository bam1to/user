<?php

namespace App\Controller;

use App\Dto\Input\UserRegistrationDto;
use App\Repository\UserRepository;
use App\Service\RedisSessionHandler;
use App\Service\ViolationsCollector;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/auth', name: 'auth_', methods: ['POST'])]
class AuthController extends AbstractController
{

    #[Route('/register', name: 'register')]
    public function registration(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        UserRepository $userRepository,
        ViolationsCollector $violationsCollector
    ): JsonResponse {
        $userData = $serializer->deserialize(
            $request->getContent(),
            UserRegistrationDto::class,
            'json'
        );

        $violations = $validator->validate($userData);

        if ($violations->count() > 0) {
            return $this->json(
                $violationsCollector->collectViolations($violations),
                Response::HTTP_BAD_REQUEST
            );
        }

        $userRepository->registerUser($userData);

        $session = $request->getSession();
        $session->start();

        return $this->json([
            'session_id' => $session->getId()
        ], Response::HTTP_CREATED);
    }

    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $session = $request->getSession();
        $session->start();

        return $this->json([
            'session_id' => $session->getId()
        ]);
    }

    #[Route('/logout', name: 'logout', methods: ['POST'])]
    public function logout(Request $request, RedisSessionHandler $redisSessionHandler, DecoderInterface $serializer): JsonResponse
    {
        $sessionId = $serializer->decode($request->getContent(), 'json')['session_id'];

        $hasDestroyed = $redisSessionHandler->destroy($sessionId);

        return $this->json([
            'has_destroyed' => $hasDestroyed
        ]);
    }
}
