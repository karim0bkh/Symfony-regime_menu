<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route('/connexion',name:'security.login',methods:['GET','POST'])]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $lastUsername = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('pages/security/login.html.twig', [
            'controller_name' => 'SecurityController',
            'last_username' => $lastUsername,
            'error'         => $error,

        ]);
    }

    #[Route('/deconnexion','security.logout')]
    public function logout()
    {
        //none
    }

    #[Route('/inscription','security.signup',methods:['GET','POST'])]
    public function inscription(UserPasswordHasherInterface $passwordHasher,Request $request , EntityManagerInterface $manager):Response
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);

        $form = $this->createForm(InscriptionType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){

            $user = $form->getData();
            

            $this->addFlash(
                'success',
                'Votre compte a été bien créé !'
            );

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('security.login');
        }

        
        return $this->render('pages/security/inscription.html.twig',[
        'form'=>$form->createView()
       ]);
    }
}
