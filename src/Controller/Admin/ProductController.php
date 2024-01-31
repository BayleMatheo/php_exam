<?php

namespace App\Controller\Admin;


use App\Entity\Article;
use App\Entity\Stock;
use App\Form\ProductsFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
    public function add(Request $request, EntityManagerInterface $em, Security $security): Response
    {
        $article = new Article();
        $articleForm = $this->createForm(ProductsFormType::class, $article);

        $articleForm->handleRequest($request);

        //dd($articleForm->isSubmitted() && $articleForm->isValid() ? "OKOK" : "Invalid");

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $nom = $article->getNom();
            $article->setNom($nom);

            $prix = $article->getPrix() * 100;
            $article->setPrix($prix);

            $user = $security->getUser();
            $article->setIdUser($user);

            $nbArticle = $articleForm->get('nb_article')->getData();

            $stock = new Stock();
            $stock->setArticle($article);
            $stock->setNbArticle($nbArticle);

            $image = $articleForm->get('lien_de_image')->getData();

            if($image) {
                $originalFilename  = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME );
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                try{
                    $image->move(
                        $this->getParameter('kernel.project_dir').'/public/uploads',
                        $newFilename
                    );
                    $article->setLienDeImage($newFilename);
                }catch (FileException $e){
                    return new Response($e->getMessage());
                }
            }

            $em->persist($article);
            $em->persist($stock);
            $em->flush();

            $this->addFlash('success', 'Produit ajouté avec succès');

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
    public function edit(Article $article,Request $request, EntityManagerInterface $em): Response
    {
        $articleForm = $this->createForm(ProductsFormType::class, $article);

        $articleForm->handleRequest($request);

        $prix = $article->getPrix() / 100;
        $article->setPrix($prix);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $nom = $article->getNom();
            $article->setNom($nom);

            $prix = $article->getPrix() * 100;
            $article->setPrix($prix);

            $em->persist($article);
            $em->flush();

            $stocks = $article->getStocks();
            $nbArticle = $article->getNbArticle();

            foreach ($stocks as $stock) {
                $stock->setNbArticle($nbArticle);
                $em->persist($stock);
            }

            $em->flush();

            $this->addFlash('success', 'Produit modifié avec succès');


            return $this->redirectToRoute('admin_products_index');
        }

        return $this->render('admin/product/edit.html.twig', [
            'articleForm' => $articleForm->createView(),
        ]);
    }
}