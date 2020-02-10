<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use App\Entity\Season;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{
    /**
     * @Route("/wild", name="wild_index")
     * @return Response
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table'
            );
        }
        return $this->render('index.html.twig', [
            'programs' => $programs,
        ]);
    }

    /**
     * @Route("/wild/show/{slug}", defaults={"slug" = null}, requirements={"slug"="[a-z0-9-]+"}, methods={"GET","POST"}, name="wild_show")
     * @param $slug
     * @return Response
     */
    public function show(?string $slug): Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = str_replace(' ', '-', $slug);
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with ' . $slug . ' title, found in program\'s table.'
            );
        }
        return $this->render('show.html.twig', [
            'program' => $program,
            'slug' => $slug,
        ]);
    }

    /**
     * @Route("wild/category/{categoryName}",  name="wild_category")
     * @param string $categoryName
     * @return Response
     */
    public function showByCategory(string $categoryName): Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' => $category], ['id' => 'desc'], 3);

        return $this->render('category.html.twig', [
            'programs' => $programs,
        ]);
    }

    /**
     * @Route("/wild/program/{slug}", defaults={"slug" = null}, requirements={"slug"="[a-z0-9-]+"}, methods={"GET","POST"}, name="wild_show")
     * @param $slug
     * @return Response
     */
    public function showByProgram(?string $slug): Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = str_replace(' ', '-', $slug);
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );

        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with ' . $slug . ' title, found in program\'s table.'
            );
        }

        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findAll();

        return $this->render('program.html.twig', [
            'program' => $program,
            'season' => $season,
            'slug' => $slug,
        ]);
    }

    /**
     * @Route("/wild/season/{id}", name="wild_season")
     * @param int $id
     * @return Response
     */
    public function showBySeason(int $id): Response
    {
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['number' => mb_strtolower($id)]);
        if (!$season) {
            throw $this->createNotFoundException(
                'No program with ' . $id . ' number, found in season\'s table.'
            );
        }

        return $this->render('season.html.twig', [
            'season' => $season,
            'id' => $id
        ]);
    }
}
