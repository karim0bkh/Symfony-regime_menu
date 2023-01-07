<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserController extends AbstractController
{
    #[Route('/utilisateur/edit/{id}', name: 'user.edit')]
    #[Security("is_granted('ROLE_USER') and user === currentuser")]
    public function index(UserPasswordHasherInterface $hasher ,EntityManagerInterface $manager ,Request $request ,User $currentuser): Response
    {
        

            $form = $this->createForm(UserType::class , $currentuser);

            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                $user =$form->getData();
                $manager->persist($user);

                $this->addFlash(
                    'success',
                    'Votre compte a été bien modifié !'
                );
    

                $manager->flush();

                return $this->redirectToRoute("app_regime");
            }


        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
