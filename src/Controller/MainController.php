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
     * @Route("/liste_streamers", name="streamers")
     */
    public function listeStreamers()
    {
        return $this->render('main/streamers.html.twig');
    }

    /**
     * @Route("/liste_jeux", name="liste")
     */
    public function listeJeux(NotesRepository $n)
    {
        // Appel de tout les jeux
        $jeux = $this->getDoctrine()->getRepository(Jeux::class)->findAll();
        $moyenne = $n->avgNote();
        $total = $n->noteCount();
        
        if ($moyenne) {
            foreach ($moyenne as $moy) {
                $mm[$moy["jeu_id"]] = $moy["moyenne"];
            }
        }

        return $this->render('main/liste.html.twig', [
            'total' => $total,
            'moyenne' => $mm,
            'jeux' => $jeux,
        ]);
    }

    /**
     * @Route("/fiche/{id}", name="fiche", methods={"POST", "GET"})
     */
    public function fiche(Request $request, Jeux $jeux, EntityManagerInterface $em, NotesRepository $n): Response
    {
        $form = $this->createForm(CommentairesType::class);
        $form->handleRequest($request);
        $moyenne = $n->targetAvg($jeux->getId());
    
        if ($moyenne == null) {
            $moyenne['moyenne'] = "Ce jeu ne dispose pas de note";
        } 

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire = $form->getData();
            $commentaire->setCreatedAt(new \DateTime("NOW"));
            $jeux->addCommentaire($commentaire);
    
            $em->persist($commentaire);
            $em->flush();
    
            return $this->redirectToRoute($request->getUri());
        }
    
        return $this->render('main/fiche.html.twig', [
            'moyenne' => $moyenne,
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
