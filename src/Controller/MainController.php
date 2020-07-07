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
use App\Entity\Users;
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
        //$totalQuery = $n->noteCountQuery(); //Fonction qui donnerait le nombre de note par jeu. (optionnel)
        
        if ($moyenne) {
            foreach ($moyenne as $moy) {
                $mm[$moy["jeu_id"]] = $moy["moyenne"];
            }
        }

        return $this->render('main/liste.html.twig', [
            //'totalQuery' => $totalQuery, //Fonction qui donnerait le nombre de note par jeu. (optionnel)
            'total' => $total,
            'moyenne' => $mm,
            'jeux' => $jeux,
        ]);
    }

    /**
     * @Route("/fiche/{id}", name="fiche", methods={"POST", "GET"})
     */
    public function fiche(Request $request, Jeux $jeux, EntityManagerInterface $em, NotesRepository $n, CommentairesRepository $c): Response
    {
        $form = $this->createForm(CommentairesType::class);
        $form->handleRequest($request);
        $moyenne = $n->targetAvg($jeux->getId());
        $commentaires = $jeux->getCommentaires();
        
        $commentaire = [];
        foreach($commentaires as $commentaire) {
             $commentaire->pseudo=$c->authorComment($commentaire->getId())['pseudo'];
        }

        if ($moyenne == null) {
            $moyenne['moyenne'] = "Ce jeu ne dispose pas de note";
        } 

        if ($form->isSubmitted() && $form->isValid()) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
            $limited = $c->hasCommented($this->get('security.token_storage')->getToken()->getUser()->getId(), $jeux->getId());
            
            if ($limited) {
                $this->addflash('erreur1', 'Vous avez déjà commenté ce jeu !');
            }else{
                $commentaire = $form->getData();
                $commentaire->setCreatedAt(new \DateTime("NOW"));
                $commentaire->setAuteur($this->get('security.token_storage')->getToken()->getUser());
                $jeux->addCommentaire($commentaire);
                
                $em->persist($commentaire);
                $em->flush();
                $this->addflash('message', 'Commentaire posté avec succès ! Merci pour ta participation :)');

        
                return $this->redirectToRoute('liste');
            }
        } else {
            $this->addflash('erreur', 'Vous devez vous connecter pour faire cela !');

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
    public function new(Request $request, Jeux $jeux, NotesRepository $n): Response
    {
        $note = new Notes();
        $form = $this->createForm(NotesType::class, $note);
        $form->handleRequest($request);
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        if ($form->isSubmitted() && $form->isValid()) {
            $limited = $n->hasVoted($this->get('security.token_storage')->getToken()->getUser()->getId(), $jeux->getId());
            if ($limited) {
                $this->addflash('erreur2', 'Vous avez déjà noté ce jeu !');
            }else{
            $entityManager = $this->getDoctrine()->getManager();
            $var = $form->getData();
            $var->setJeu($jeux);
            $var->setUser($this->get('security.token_storage')->getToken()->getUser());
            $entityManager->persist($note);
            $entityManager->flush();
            $this->addflash('message', 'La note à été traités et fait est maintenant comptabilisé dans la moyenne officiel du jeu ! Merci pour ta participation :)');


            return $this->redirectToRoute('liste');
        }}

        return $this->render('notes/new.html.twig', [
            'jeux' => $jeux,
            'note' => $note,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/conditions_generales_d'utilisation", name="cgu")
     */
    public function cgu()
    {
        return $this->render('main/cgu.html.twig');
    }
}
