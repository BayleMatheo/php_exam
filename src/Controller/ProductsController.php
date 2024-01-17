<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/produits', name: 'products_')]
class ProductsController extends AbstractController
{
    #[Route('/', name:'index')]
    public function index(): Response
    {
        return $this->render('products/index.html.twig');
    }
    #[Route('/{Article.id}', name:'details')]
    public function details(Article $article): Response
    {
        dd($article);
        return $this->render('products/details.html.twig');
    }
}