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

        $existingProgression = $em->getRepository(Progression::class)->findOneBy([
            'utilisateur' => $user,
            'cours' => $course,
        ]);

        if ($existingProgression) {
            return new JsonResponse(['message' => 'User is already registered for this course.'], Response::HTTP_OK);
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

        if (!$userId || !$courseId) {
            return new JsonResponse(['error' => 'User ID and Course ID are required.'], Response::HTTP_BAD_REQUEST);
        }

        $progression = $em->getRepository(Progression::class)->findOneBy([
            'utilisateur' => $userId,
            'cours' => $courseId,
        ]);

        if (!$progression) {
            return new JsonResponse(['error' => 'No progression found for this user and course.'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'statut' => $progression->getStatut(),
            'pourcentage' => $progression->getProgPourcentage(),
            'updated_at' => $progression->getUpdatedAt()->format('Y-m-d H:i:s'),
        ], Response::HTTP_OK);
    }

    #[Route('/api/progression/update', name: 'api.progression.update', methods: ['POST'])]
    public function updateProgression(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $courseId = $data['cours_id'] ?? null;
        $userId = $data['utilisateur_id'] ?? null;
        $currentVideoIndex = $data['current_video_index'] ?? null;
        $totalVideos = $data['total_videos'] ?? null;

        if (!$courseId || !$userId || $currentVideoIndex === null || !$totalVideos) {
            return new JsonResponse(['error' => 'Missing required fields.'], Response::HTTP_BAD_REQUEST);
        }

        $progression = $em->getRepository(Progression::class)->findOneBy([
            'utilisateur' => $userId,
            'cours' => $courseId,
        ]);

        if (!$progression) {
            return new JsonResponse(['error' => 'Progression not found.'], Response::HTTP_NOT_FOUND);
        }

        $percentage = (int) round((($currentVideoIndex + 1) / $totalVideos) * 100);

        if ($percentage > $progression->getProgPourcentage()) {
            $progression->setProgPourcentage($percentage);
            $progression->setUpdatedAt(new \DateTimeImmutable());

            if ($percentage === 100) {
                $progression->setStatut('completed');
            }

            $em->flush();
        }

        return new JsonResponse([
            'message' => 'Progression updated.',
            'new_percentage' => $progression->getProgPourcentage(),
            'statut' => $progression->getStatut(),
        ], Response::HTTP_OK);
    }

    #[Route('/api/progression/user/{userId}', name: 'api.progression.by_user', methods: ['GET'])]
    public function getProgressionsByUser(int $userId, EntityManagerInterface $em): JsonResponse
    {
        $user = $em->getRepository(User::class)->find($userId);
        if (!$user) {
            return new JsonResponse(['error' => 'User not found.'], Response::HTTP_BAD_REQUEST);
        }

        $progressions = $em->getRepository(Progression::class)->findBy(['utilisateur' => $user]);
        $data = [];
        foreach ($progressions as $prog) {
            $data[] = [
                'cours_id' => $prog->getCours()->getId(),
                'statut' => $prog->getStatut(),
                'pourcentage' => $prog->getProgPourcentage(),
                'updated_at' => $prog->getUpdatedAt()->format('Y-m-d H:i:s'),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
