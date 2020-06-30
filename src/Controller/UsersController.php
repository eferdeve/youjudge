<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\EditProfileType;
use App\Form\UsersType;
use App\Repository\UsersRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/users")
 */
class UsersController extends AbstractController
{

    /**
     * @Route("/", name="users_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('users/index.html.twig');
    }

    /**
     * @Route("/edit", name="users_edit")
     */
    public function editProfile(Request $request): Response
    {     
        $user = $this->getUser();   
        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addflash('message', 'Profile mis à jour');

            return $this->redirectToRoute('users_index');
        }

        return $this->render('users/editprofile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/editpass", name="userspass_edit")
     */
    public function editPass(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {     
        if($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();

            //Vérification mot de passe identique
            if($request->request->get('pass') == $request->request->get('pass2')) {
                $user->setPassWord($passwordEncoder->encodePassword($user, $request->request->get('pass')));
                $em->flush();
                $this->addflash('message', 'Mot de pass mis à jour avec succès !');

                return $this->redirectToRoute('users_index');
            }else{
                $this->addflash('error', 'Les deux mots de passe ne sont pas identiques');
            }
        }

        return $this->render('users/editpass.html.twig');
    }

    /**
     * @Route("/{id}", name="users_show", methods={"GET"})
     */
    public function show(Users $user): Response
    {
        return $this->render('users/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}", name="users_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Users $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_list');
    }
}
