<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(ArticleRepository $articleRepository, UserInterface $user): Response
    {
        return $this->render('main/index.html.twig', [
            'articles' => $articleRepository->findBy([], ['id' => 'asc']),
            'solde' => $user->getSolde(),
            'photoDeProfil' => $user->getPhotoDeProfil(),
        ]);
    }
}