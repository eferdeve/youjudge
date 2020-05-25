<?php

namespace App\Controller;

use App\Entity\Commentaires;
use App\Form\CommentairesType;
use App\Repository\CommentairesRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Jeux;
use App\Repository\JeuxRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;




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

    /**
     * @Route("/fiche/{id}", name="fiche", methods={"POST", "GET"})
     */
    public function fiche($id, Jeux $jeux, Request $request): Response
    {
        $id = (int)$request->get('id');
        $commentaires = $this->getDoctrine()->getRepository(Jeux::class)->find($id);

        $commentaire = new Commentaires();
        $commentaire->setCreatedAt(new \DateTime("NOW"));
        $request = Request::createFromGlobals();
        $commentaire->setJeu($jeux);
        $form = $this->createForm(CommentairesType::class, $commentaire);
        $form->handleRequest($request);

        //test variable
        //dd($id);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('liste');
        }
        return $this->render('main/fiche.html.twig', [
            'commentaires' => $commentaires,
            'jeux' => $jeux,
            'form' => $form->createView(),
        ]);
    }
}
