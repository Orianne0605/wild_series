<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{
    /**
     * @Route("/wild", name="wild_index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if (!$programs){
            throw $this->createNotFoundException(
                'No program found in program\'s table'
            );
        }
        return $this->render('wild/index.html.twig', [
            'programs' => $programs
        ]);
    }

    /**
     * @Route("/wild/show/{slug}", defaults={"slug" = null}, requirements={"slug"="[a-z0-9-]+"}, methods={"GET","POST"}, name="wild_show")
     * @param $slug
     * @return Response
     */
    public function show($slug = "Aucune série sélectionnée, veuillez choisir une série"): Response
    {
        if (!$slug){
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = str_replace('-', ' ', $slug);
        $slug = ucwords($slug);
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this ->createNotFoundException(
                'No program with' .$slug.' title, found in program\'s table.'
            );
        }
        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug' => $slug,
        ]);
    }

    /**
     * @Route("wild/category/{categoryName}",  name="wild_category")
     * @param $categoryName
     * @return Response
     */
    public function showByCategory(string $categoryName): Response

    {
        $category= $this->getDoctrine()
                        ->getRepository(Category::class)
                        ->findAll();

        $programs= $this->getDoctrine()
                        ->getRepository(Program::class)
                        ->findBy(['category' => $category], ['id' => 'desc'], 3);

        return $this->render('wild/category.html.twig', [
            'programs'=> $programs,
        ]);
}
}

