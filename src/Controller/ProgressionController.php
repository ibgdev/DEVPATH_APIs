<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Progression;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProgressionController extends AbstractController
{
    #[Route('/api/progression/register', name: 'api.progression.register', methods: ['POST'])]
    public function register(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $courseId = $data['cours_id'] ?? null;
        $userId = $data['utilisateur_id'] ?? null;
        
        if (!$courseId || !$userId) {
            return new JsonResponse(['error' => 'Course ID and User ID are required.'], Response::HTTP_BAD_REQUEST);
        }
        
        $course = $em->getRepository(Cours::class)->find($courseId);
        $user = $em->getRepository(User::class)->find($userId);
        if (!$course || !$user) {
            return new JsonResponse(['error' => 'Invalid Course ID or User ID.'], Response::HTTP_BAD_REQUEST);
        }
        $progression = new Progression();
        $progression->setStatut('registered');
        $progression->setProgPourcentage(0);
        $progression->setUpdatedAt(new \DateTimeImmutable());
        $progression->setUtilisateur($user);
        $progression->setCours($course);
        $em->persist($progression);
        $em->flush();
        return new JsonResponse(['message' => 'Course registration successful.'], Response::HTTP_OK);
        
    }
    #[Route('/api/progression/check', name: 'api.progression.check', methods: ['GET'])]
    public function checkProgression(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $userId = (int) $request->query->get('user_id');
        $courseId = (int) $request->query->get('cours_id');
        
        // Validate if both userId and courseId are provided
        if (!$userId || !$courseId) {
            return new JsonResponse(['error' => 'User ID and Course ID are required.'], Response::HTTP_BAD_REQUEST);
        }
        
        $progression = $em->getRepository(Progression::class)->findOneBy([
            'utilisateur' => $userId,
            'cours' => $courseId,
        ]);
        
        // Check if progression is found
        if (!$progression) {
            return new JsonResponse(['error' => 'No progression found for this user and course.'], Response::HTTP_NOT_FOUND);
        }
        
        // Return the progression data
        return new JsonResponse([
            'statut' => $progression->getStatut(),
            'pourcentage' => $progression->getProgPourcentage(),
            'updated_at' => $progression->getUpdatedAt()->format('Y-m-d H:i:s'),
        ], Response::HTTP_OK);
    }
    

}
