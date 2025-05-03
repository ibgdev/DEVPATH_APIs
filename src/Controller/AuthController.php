<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

final class AuthController extends AbstractController
{
    private $jwtManager;

    // Inject JWTTokenManagerInterface instead of JWTManager
    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email || !$password) {
            return new JsonResponse(['error' => 'Email and password are required.'], 400);
        }

        $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
            return new JsonResponse(['error' => 'Invalid email or password.'], 401);
        }

        // Create the JWT token with the email instead of username
        $token = $this->jwtManager->create($user);

        return new JsonResponse(['token' => $token], 200);
    }

    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;
        $username = $data['username'] ?? 'defaultUsername'; // Default username if not provided

        // Check if email and password are provided
        if (!$email || !$password) {
            return new JsonResponse(['error' => 'Email and password are required.'], 400);
        }

        // Check if the email is already in use
        if ($em->getRepository(User::class)->findOneBy(['email' => $email])) {
            return new JsonResponse(['error' => 'Email already in use.'], 409);
        }

        // Check if the username is already taken
        if ($em->getRepository(User::class)->findOneBy(['username' => $username])) {
            return new JsonResponse(['error' => 'Username already taken.'], 409);
        }

        // Create a new user
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($passwordHasher->hashPassword($user, $password)); // Properly hash password
        $user->setRoles(['ROLE_USER']);
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setUsername($username);  // Set the username

        // Persist the user to the database
        $em->persist($user);
        $em->flush();

        // Return success message
        return new JsonResponse(['message' => 'User registered successfully.'], 201);
    }


    #[Route('/api/user/{username}', name: 'api_get_user_by_username', methods: ['GET'])]
    public function getUserByUsername(string $username, EntityManagerInterface $em): JsonResponse
    {
        // Fetch user by username
        $user = $em->getRepository(User::class)->findOneBy(['username' => $username]);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found.'], 404);
        }

        // Prepare the user data
        $user_data = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
            'createdAt' => $user->getCreatedAt()->format('Y-m-d H:i:s'),  // Optional: Format the createdAt field
        ];

        // Return the user data as JSON
        return new JsonResponse($user_data, 200);
    }


}