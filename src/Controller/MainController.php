<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('main/index.html.twig',[
            'article' => $articleRepository->findBy([],
                ['id' => 'asc'])
        ]);
    }
}
