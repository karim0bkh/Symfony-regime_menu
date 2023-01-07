<?php

namespace App\Controller;

use App\Repository\RegimeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    
    #[Route('/','home.index' , methods:['GET'])]
    public function index(
        RegimeRepository $regimeRepository
    ): Response
    {
        return $this->render('pages/home.html.twig',[
            'regimes'=>$regimeRepository->findRegimePublic(5)
        ]);
    }
}




