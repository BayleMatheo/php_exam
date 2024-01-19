<?php

namespace App\Controller\Admin;


use App\Entity\Article;
use App\Form\ProductFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


#[Route('/admin/produits', name:'admin_products_')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/product/index.html.twig');
    }
    #[Route('/add', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, Security $security): Response
    {
        $article = new Article();
        $articleForm = $this->createForm(ProductFormType::class, $article);

        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $slug = $slugger->slug($article->getNom());
            $article->setNom($slug);

            $prix = $article->getPrix() * 100;
            $article->setPrix($prix);

            $user = $security->getUser();
            $article->setIdUser($user);

            $em->persist($article);
            $em->flush();
            $this->addFlash('success', 'Article ajouté avec succès');

            return $this->redirectToRoute('admin_products_index');
        }

        return $this->render('admin/product/add.html.twig', [
            'articleForm' => $articleForm->createView(),
        ]);
    }
    #[Route('/delete', name: 'delete')]
    public function delete(): Response
    {

        return $this->render('admin/product/index.html.twig');
    }
    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Article $article,Request $request, EntityManagerInterface $em, SluggerInterface $slugger, Security $security): Response
    {

        $prix = $article->getPrix() / 100;
        $article->setPrix($prix);

        $articleForm = $this->createForm(ProductFormType::class, $article);

        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {

            $slug = $slugger->slug($article->getNom());
            $article->setNom($slug);

            $prix = $article->getPrix() * 100;
            $article->setPrix($prix);

            $user = $security->getUser();
            $article->setIdUser($user);

            $em->persist($article);
            $em->flush();
            $this->addFlash('success', 'Article modifier avec succès');

            return $this->redirectToRoute('admin_products_index');
        }

        return $this->render('admin/product/edit.html.twig', [
            'articleForm' => $articleForm->createView(),
        ]);
    }
}