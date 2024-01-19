<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Stock;
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
    #[Route('/{id}', name:'details')]
    public function details(Article $article, Stock $stock): Response
    {
        return $this->render('products/details.html.twig',[
            'article' => $article,
            'nb_articles_en_stock' => $stock->getNbArticle(),
        ]);
    }
}