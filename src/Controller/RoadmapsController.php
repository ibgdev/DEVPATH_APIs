<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\RoadmapCours;
use App\Entity\Roadmaps;
use App\Form\RoadmapCourseType;
use App\Form\RoadmapsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RoadmapsController extends AbstractController
{

    // Crud pages

    #[Route('/roadmaps', name: 'crud.roadmaps.all')]
    public function index(EntityManagerInterface $em): Response
    {   
        $roadmaps = $em->getRepository(Roadmaps::class)->findAll();
        return $this->render('roadmap/index.html.twig', [
            'roadmaps' => $roadmaps,
        ]);
    }

    #[Route('/roadmap/add', name: 'crud.roadmaps.add')]
    public function add(EntityManagerInterface $em, Request $request): Response
    {
        $roadmap = new Roadmaps();
        $form = $this->createForm(RoadmapsType::class, $roadmap);   
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($roadmap);
            $this->addFlash('success', 'Roadmap added successfully');
            $em->flush();
            return $this->redirectToRoute('crud.roadmaps.all');
        }
        return $this->render('roadmap/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/roadmap/edit/{id}', name: 'crud.roadmaps.edit')]
    public function edit(EntityManagerInterface $em, Request $request, Roadmaps $roadmap): Response
    {
        $form = $this->createForm(RoadmapsType::class, $roadmap);   
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($roadmap);
            $this->addFlash('success', 'Roadmap updated successfully');
            $em->flush();
            return $this->redirectToRoute('crud.roadmaps.all');
        }
        return $this->render('roadmap/edit.html.twig', [
            'form' => $form,
            'roadmap' => $roadmap,
        ]);
    }

    #[Route('/roadmap/delete/{id}', name: 'crud.roadmaps.delete')]
    public function delete(EntityManagerInterface $em, Roadmaps $roadmap)
    {
        $em->remove($roadmap);
        $em->flush();
        $this->addFlash('success', 'roadmap deleted successfully');
        return $this->redirectToRoute('crud.roadmaps.all');
    }

    #[Route('/roadmap/{id}/courses', name: 'crud.roadmap.courses')]
    public function courses(EntityManagerInterface $em, Roadmaps $roadmap): Response
    {
        $courses = $roadmap->getRoadmapCours();
        return $this->render('roadmap/courses.html.twig', [
            'courses' => $courses,
            'roadmap' => $roadmap,
        ]);
    }
    #[Route('/roadmap/{id}/courses/add', name: 'crud.roadmap.courses.add')]
    public function courses_add(EntityManagerInterface $em, Request $request, Roadmaps $roadmap): Response
    {
        $Rcourse = new RoadmapCours();
        $form = $this->createForm(RoadmapCourseType::class, $Rcourse);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Rcourse->setRoadmap($roadmap);
            $em->persist($Rcourse);
            $em->flush();
            $this->addFlash('success', 'Course added successfully');
        }
        return $this->render('roadmap/course_add.html.twig', [
            'form' => $form,
            'rcourse' => $Rcourse,
        ]);
    }

    //api pages
    #[Route('/api/roadmaps', name: 'api.roadmaps.all')]
    public function api_get_roadmaps(EntityManagerInterface $em): Response
    {   
        $roadmaps = $em->getRepository(Roadmaps::class)->findAll();
        if (empty($roadmaps)) {
            return $this->json(['message' => 'No courses found']);
        }
        $data = array_map(function ($roadmap) {
            return [
                'id' => $roadmap->getId(),
                'titre' => $roadmap->getTitre(),
                'description' => $roadmap->getDescription(),            
            ];
        }, $roadmaps);
        return $this->json($data);
    }

    //TODO : add crud for roadmaps courses  
}
