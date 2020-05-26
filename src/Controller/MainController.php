<?php

namespace App\Controller;

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
    
            return $this->redirectToRoute('liste');
        }
    
        return $this->render('main/fiche.html.twig', [
            'commentaires' => $jeux->getCommentaires(),
            'jeux' => $jeux,
            'form' => $form->createView(),
        ]);
    }
}
