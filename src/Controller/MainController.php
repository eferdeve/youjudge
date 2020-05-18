<?php

namespace App\Controller;

use App\Entity\Jeux;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/liste", name="liste")
     */
    public function ListeJeux()
    {
        // Appel de tout les jeux
        $jeux = $this->getDoctrine()->getRepository(Jeux::class)->findAll();

        return $this->render('main/liste.html.twig', [
            'jeux' => $jeux,
        ]);
    }
}
