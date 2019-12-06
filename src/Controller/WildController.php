<?php
// src/Controller/WildController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{
    /**
     * @Route("/wild", name="wild_index")
     */
    public function index(): Response
    {
        return $this->render('wild/index.html.twig', [
            'website' => 'Wild Series',
        ]);
    }

    /**
     * @Route("/wild/show/{slug}", methods={"GET","POST"}, name="wild_show")
     * @param string $slug
     * @return Response
     */
    public function show(string $slug = "Aucune série sélectionnée, veuillez choisir une série"): Response
    {
        $slug = str_replace('-', ' ', $slug);
        $slug = ucwords($slug);
        return $this->render('wild/show.html.twig', [
            'slug' => $slug
        ]);
    }
}

