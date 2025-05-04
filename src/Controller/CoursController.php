<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Videos;
use App\Form\CoursType;
use App\Form\VideoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CoursController extends AbstractController
{
    // Crud pages

    #[Route('/courses', name: 'crud.course.all')]
    public function index(EntityManagerInterface $em): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }
        $courses = $em->getRepository(Cours::class)->findAll();
        return $this->render('cours/index.html.twig', [
            'courses' => $courses,
        ]);
    }

    #[Route('/course/add', name: 'crud.course.add')]
    public function add(Request $request, EntityManagerInterface $em)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }
        $course = new Cours();
        $form = $this->createForm(CoursType::class, $course);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($course);
            $em->flush();
            $this->addFlash('success', 'Course added successfully');
            return $this->redirectToRoute('crud.course.all');
        }
        return $this->render('cours/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/course/edit/{id}', name: 'crud.course.edit')]
    public function edit(Request $request, EntityManagerInterface $em, Cours $course)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(CoursType::class, $course);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($course);
            $em->flush();
            $this->addFlash('success', 'Course edited successfully');
            return $this->redirectToRoute('crud.course.all');
        }
        return $this->render('cours/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/course/delete/{id}', name: 'crud.course.delete')]
    public function delete(EntityManagerInterface $em, Cours $course)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }
        $em->remove($course);
        $em->flush();
        $this->addFlash('success', 'Course deleted successfully');
        return $this->redirectToRoute('crud.course.all');
    }


    #[Route('/course/{id}/videos', name: 'crud.course.videos')]
    public function videos(EntityManagerInterface $em, Cours $course): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }
        $videos = $course->getVideos();
        return $this->render('cours/videos.html.twig', [
            'course' => $course,
            'videos' => $videos,
        ]);
    }
    #[Route('/course/{id}/video/add', name: 'crud.video.add')]
    public function video_add(EntityManagerInterface $em, Request $request, Cours $course): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }
        $video = new Videos();
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $video->setCours($course);
            $em->persist($video);
            $em->flush();
            $this->addFlash('success', 'Video added successfully');
            return $this->redirectToRoute('crud.course.videos', ['id' => $course->getId()]);
        }
        return $this->render('cours/video_add.html.twig', [
            'form' => $form,
            'course' => $course,
        ]);
    }

    // Api Pages

    #[Route('/api/courses/recent', name: 'api.courses.recent')]
    public function api_get_courses_recent(EntityManagerInterface $em): Response
    {
        $courses = $em->getRepository(Cours::class)->findAllRecent();
        if (empty($courses)) {
            return $this->json(['message' => 'No courses found']);
        }
        $data = array_map(function ($course) {
            return [
                'id' => $course->getId(),
                'titre' => $course->getTitre(),
                'description' => $course->getDescription(),
                'image_url' => $course->getImageUrl(),
                'created_at' => $course->getCreatedAt()->format('Y-m-d'),
            ];
        }, $courses);
        return $this->json($data);
    }
    
    #[Route('/api/courses', name: 'api.courses')]
    public function api_get_courses(EntityManagerInterface $em): Response
    {
        $courses = $em->getRepository(Cours::class)->findAll();
        if (empty($courses)) {
            return $this->json(['message' => 'No courses found']);
        }
        $data = array_map(function ($course) {
            return [
                'id' => $course->getId(),
                'titre' => $course->getTitre(),
                'description' => $course->getDescription(),
                'image_url' => $course->getImageUrl(),
                'created_at' => $course->getCreatedAt()->format('Y-m-d'),
            ];
        }, $courses);
        return $this->json($data);
    }
    #[Route('/api/courses/{id}', name: 'api.course.id')]
    public function api_get_course_id(EntityManagerInterface $em, int $id): Response
    {
        $course = $em->getRepository(Cours::class)->find($id);
        if (!$course) {
            return $this->json(['message' => 'No courses found']);
        }
        $data = [
            'id' => $course->getId(),
            'titre' => $course->getTitre(),
            'description' => $course->getDescription(),
            'image_url' => $course->getImageUrl(),
            'created_at' => $course->getCreatedAt()->format('Y-m-d'),
        ];
        return $this->json($data);
    }

    #[Route('/api/courses/{id}/videos', name: 'api.course.videos', methods: ['GET'])]
    public function api_get_videos(int $id, Cours $course): Response
    {

        if (!$course) {
            return $this->json(['message' => 'Course not found'], 404);
        }

        $videos = $course->getVideos(); 

        $data = [];

        foreach ($videos as $video) {
            $data[] = [
                'id' => $video->getId(),
                'titre' => $video->getTitre(),
                'url' => $video->getUrl(),
                'ord' => $video->getOrd(),
            ];
        }

        return $this->json($data);
    }
}
