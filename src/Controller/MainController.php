<?php

namespace App\Controller;

use App\Entity\Notes;
use App\Form\NotesType;
use App\Repository\NotesRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function ListeJeux(NotesRepository $n)
    {
        // Appel de tout les jeux
        $jeux = $this->getDoctrine()->getRepository(Jeux::class)->findAll();
        $moyenne = $n->avgNote();
        
        return $this->render('main/liste.html.twig', [
            'moyenne' =>$moyenne,
            'jeux' => $jeux,
        ]);
    }

    /**
     * @Route("/fiche/{id}", name="fiche", methods={"POST", "GET"})
     */
    public function fiche(Request $request, Jeux $jeux, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CommentairesType::class);
        $form->handleRequest($request);
        
    
        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire = $form->getData();
            $commentaire->setCreatedAt(new \DateTime("NOW"));
            $jeux->addCommentaire($commentaire);
    
            $em->persist($commentaire);
            $em->flush();
    
            return $this->redirectToRoute($request->getUri());
        }
    
        return $this->render('main/fiche.html.twig', [
            'commentaires' => $jeux->getCommentaires(),
            'jeux' => $jeux,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/note/{id}", name="notes_new", methods={"GET","POST"})
     */
    public function new(Request $request, Jeux $jeux): Response
    {
        $note = new Notes();
        $form = $this->createForm(NotesType::class, $note);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $var = $form->getData();
            $var->setJeu($jeux);
            $entityManager->persist($note);
            $entityManager->flush();

            return $this->redirectToRoute('liste');
        }

        return $this->render('notes/new.html.twig', [
            'note' => $note,
            'form' => $form->createView(),
        ]);
    }
}
