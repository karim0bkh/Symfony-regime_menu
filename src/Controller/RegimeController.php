<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\Regime;
use App\Form\NoteType;
use App\Form\RegimeType;
use App\Form\RegimeCType;
use App\Repository\NoteRepository;
use App\Repository\RegimeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class RegimeController extends AbstractController
{
    /**
     * this controller displays all regimes
     *
     * @param Request $request
     * @param RegimeRepository $repository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    #[Route('/regime', name: 'app_regime',methods:['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request ,  RegimeRepository $repository , PaginatorInterface $paginator): Response
    {
        $regimes = $paginator->paginate(
            $repository->findBy(['user'=>$this->getUser()]), 
            $request->query->getInt('page', 1), 
            5 
        );

        return $this->render('pages/regime/index.html.twig', [
            'regimes' => $regimes,
        ]);
    }

    #[Route('/regime/{id}', name: 'regime.show',methods:['GET','POST'])]
    #[Security("is_granted('ROLE_USER') and (regime.isIsPublic() === true || user === regime.getUser())")]

    public function show(EntityManagerInterface $manager ,NoteRepository $repository ,Request $request ,Regime $regime):Response
    {
        $note = new Note();
        $form = $this->createForm(NoteType::class,$note);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $note->setUser($this->getUser())
            ->setRegime($regime);
            $deja_note = $repository->findOneBy(
                [
                    'user'=>$this->getUser(),
                    'regime'=>$regime
                ]
            );
            if(!$deja_note){
                $manager->persist($note);
                
            }else{
                $deja_note->setNote(
                    $form->getData()->getNote()
                );
                
            }
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre note a été bien prise en compte !'
            );
            return $this->redirectToRoute('regime.show',['id'=>$regime->getId()]);
        }
        return $this->render('pages/regime/show.html.twig',[
            'regime'=>$regime,
            'form' => $form->createView()

        ]);
    }

    #[Route('/regime/publique/show', name: 'regime.public',methods:['GET'])]
    //#[Security("is_granted('ROLE_USER') and regime.isIsPublic() === true")]
    public function showPublic(
        RegimeRepository $repository,
        PaginatorInterface $paginator,
        Request $request

    ):Response
    {

        $regimes =$paginator->paginate(
            $repository->findRegimePublic(null),
            $request->query->getInt('page', 1), 
            5 
        );
        return $this->render('pages/regime/show_public.html.twig',[
            'regimes'=>$regimes
        ]);
    }

    /**
     * this controller adds new regime
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/regime/nouveau/ajout',name: 'regime.new',methods:['GET','POST'])]
    public function new(EntityManagerInterface $manager , Request $request): Response
    {
        $regime = new Regime();
        $form = $this->createForm(RegimeCType::class,$regime);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $regime =$form->getData();
            $regime->setUser($this->getUser());
            $manager->persist($regime);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre régime a été créé aves succès ! '
            );
            return $this->redirectToRoute('app_regime');
            


        }
        return $this->render('pages/regime/new.html.twig',[
            'form' => $form->createView()
        ]);
    }
    

    #[Route('/regime/edit/{id}' , 'regime.edit' , methods:['GET','POST'])]
    #[Security("is_granted('ROLE_USER') and user === regime.getUser()")]
    public function edit(Regime $regime, 
                         Request $request , 
                         EntityManagerInterface $manager) : Response
    {
        
        $form = $this->createForm(RegimeType::class,$regime);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $regime = $form->getData();
            $manager->persist($regime);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre regime a été modifié aves succès ! '
            );
            return $this->redirectToRoute('app_regime');
            
        }   
        return $this->render('pages/regime/edit.html.twig',[
            'form'=> $form->createView()
        ]);
    }
    #[Route("/regime/delete/{id}","regime.delete",methods:['GET'])]
    public function delete(EntityManagerInterface $manager,Regime $regime) : Response
    {
        
        $manager->remove($regime);
        $manager->flush();
        $this->addFlash(
            'success',
            'Votre régime a été supprimé aves succès ! '
        );

        return $this->redirectToRoute('app_regime');
    }


}
