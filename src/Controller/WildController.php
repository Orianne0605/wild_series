<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{
    private $programs;

    /**
     * @Route("/wild", name="wild_index")
     * @return Response A response instance
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
    public function show($slug): Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = str_replace('-', ' ', $slug);
        $slug = ucwords($slug);
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
     * @param Program $program
     * @return WildController
     */
    public function addProgram(Program $program): self
    {
        if (!$this->programs->contains($program)) {
            $this->programs[] = $program;
            $program->setCategory($this);
        }
        return $this;
    }

    /**
     * @param Program $program
     * @return WildController
     */
    public function removeProgram(Program $program): self
    {
        if ($this->programs->contains($program)) {
            $this->programs->removeElement($program);
            // set the owning side to null (unless already changed)
            if ($program->getCategory() === $this) {
                $program->setCategory(null);
            }
        }
        return $this;
    }
}
