<?php

namespace App\Controller;

use App\Entity\Plat;
use App\Form\PlatType;
use App\Form\PlatCType;
use App\Repository\PlatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class PlatController extends AbstractController
{
    /**
     * this function displays all plats
     *
     * @param PlatRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/plat', name: 'app_plat',methods:['GET'])]
    #[IsGranted('ROLE_USER')]

    public function index(PlatRepository $repository,PaginatorInterface $paginator, Request $request): Response
    {
        $plat = $paginator->paginate(
            $repository->findBy(['user' =>$this->getUser()]), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );
        return $this->render('pages/plat/index.html.twig', [
          'plats'=>$plat
        ]);
    }
    /**
     * this adds new plat
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/plat/nouveau', name: 'new_plat',methods:['GET','POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request , EntityManagerInterface $manager) : Response
    {
        $plat = new Plat();
        $form = $this->createForm(PlatCType::class,$plat);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $plat = $form->getData();
            $plat->setUser($this->getUser());
            $manager->persist($plat);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre plat a été créé aves succès ! '
            );
            return $this->redirectToRoute('app_plat');
            
        }   

        return $this->render('pages/plat/new.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    #[Route('/plat/edit/{id}' , 'plat.edit' , methods:['GET','POST'])]
    #[Security("is_granted('ROLE_USER') and user === plat.getUser()")]
    public function edit(Plat $plat, 
                         Request $request , 
                         EntityManagerInterface $manager) : Response
    {
        
        $form = $this->createForm(PlatType::class,$plat);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $plat = $form->getData();
            $manager->persist($plat);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre plat a été modifié aves succès ! '
            );
            return $this->redirectToRoute('app_plat');
            
        }   
        return $this->render('pages/plat/edit.html.twig',[
            'form'=> $form->createView()
        ]);
    }
    #[Route("/plat/delete/{id}","plat.delete",methods:['GET'])]
    public function delete(EntityManagerInterface $manager,Plat $plat) : Response
    {
        
        $manager->remove($plat);
        $manager->flush();
        $this->addFlash(
            'success',
            'Votre plat a été supprimé aves succès ! '
        );

        return $this->redirectToRoute('app_plat');
    }

}
