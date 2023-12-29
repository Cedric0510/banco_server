<?php

// src/Controller/SecurityController.php
// src/Controller/SecurityController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['POST'])]
    public function login(Request $request, UserProviderInterface $userProvider, JWTTokenManagerInterface $JWTTokenManager, UserPasswordHasherInterface $passwordEncoder): Response
    {
        $data = json_decode($request->getContent(), true);

        $username = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        try {
            $user = $userProvider->loadUserByIdentifier($username);

            if (!$user || !$passwordEncoder->isPasswordValid($username, $password)) {
                throw new AuthenticationException('Impossible de ce connectée mot de passe incorrecte');
            }

            $token = $JWTTokenManager->create($user);

            return $this->json(['token' => $token]);
        } catch (AuthenticationException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
    }



}