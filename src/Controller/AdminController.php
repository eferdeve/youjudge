<?php

namespace App\Controller;

use App\Entity\Commentaires;
use App\Repository\CommentairesRepository;
use App\Entity\Jeux;
use App\Form\JeuxType;
use App\Repository\JeuxRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{

    /**
     * @Route("/users", name="user_list", methods={"GET"})
     */
    public function userList(UsersRepository $UsersRepository)
    {
        return $this->render('admin/userList.html.twig', [
            'users' => $UsersRepository->findAll(),
        ]);
    }

    /**
     * @Route("/jeux", name="jeux_index", methods={"GET"})
     */
    public function index(JeuxRepository $jeuxRepository): Response
    {
        return $this->render('admin/game_index.html.twig', [
            'jeuxes' => $jeuxRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="jeux_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $jeux = new Jeux();
        $form = $this->createForm(JeuxType::class, $jeux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($jeux);
            $entityManager->flush();

            return $this->redirectToRoute('jeux_index');
        }

        return $this->render('admin/newgame.html.twig', [
            'jeux' => $jeux,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/", name="jeux_dashboard", methods={"GET"})
     */
    public function dashboard(JeuxRepository $jeuxRepository): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'jeuxes '=> $jeuxRepository->findAll(),
        ]);
    }

    /**
     * @Route("/commentaires", name="commentsList", methods={"GET"})
     */
    public function comments(CommentairesRepository $commentairesRepository): Response
    {
        return $this->render('admin/comments_index.html.twig', [
            'commentaires' => $commentairesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/commentaires/{id}", name="comment", methods={"GET"})
     */
    public function comment(Commentaires $commentaire): Response
    {
        return $this->render('admin/comment_show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    /**
     * @Route("/jeux/{id}", name="jeux_show", methods={"GET"})
     */
    public function show(Jeux $jeux): Response
    {
        return $this->render('admin/game_show.html.twig', [
            'jeux' => $jeux,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="jeux_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Jeux $jeux): Response
    {
        $form = $this->createForm(JeuxType::class, $jeux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('jeux_index');
        }

        return $this->render('admin/game_edit.html.twig', [
            'jeux' => $jeux,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="jeux_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Jeux $jeux): Response
    {
        if ($this->isCsrfTokenValid('delete'.$jeux->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($jeux);
            $entityManager->flush();
        }

        return $this->redirectToRoute('jeux_index');
    }
}
