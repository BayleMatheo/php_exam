<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Invoice;
use App\Entity\Stock;
use App\Form\InvoiceType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/cart', name: 'cart_')]
class CartController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(ArticleRepository $articleRepository, SessionInterface $session)
    {
        $panier = $session->get('panier', []);

        $data = [];
        $total = 0;

        foreach ($panier as $id => $quantite) {
            $article = $articleRepository->find($id);

            if ($article) {
                $data[] = [
                    'article' => $article,
                    'quantite' => $quantite
                ];
                $total += $article->getPrix() * $quantite;
            } else {
                unset($panier[$id]);
                $session->set('panier', $panier);
            }
        }

        return $this->render('cart/index.html.twig', compact('data'));
    }
    #[Route('/add/{id}', name: 'add')]
    public function add(Article $article, SessionInterface $session, EntityManagerInterface $em)
    {
        $id = $article->getId();

        $panier = $session->get('panier', []);

        if (!array_key_exists($id, $panier)) {
            $panier[$id] = 0;
        }
        $stock = $em->getRepository(Stock::class)->findOneBy(['article' => $article]);

        if ($stock && $stock->getNbArticle() > $panier[$id]) {
            $panier[$id]++;
            $session->set('panier', $panier);
        } else {
            $this->addFlash('error', 'La quantité demandée n\'est pas disponible en stock.');
        }

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function remove(Article $article, SessionInterface $session)
    {
        $id = $article->getId();

        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
            if ($panier[$id] > 1) {
                $panier[$id]--;
            } else {
                unset($panier[$id]);
            }
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('cart_index');
    }
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Article $article, SessionInterface $session)
    {
        $id = $article->getId();

        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }


        $session->set('panier', $panier);

        return $this->redirectToRoute('cart_index');
    }
    #[Route('/empty', name: 'empty')]
    public function empty(SessionInterface $session)
    {
        $session->remove('panier');

        return $this->redirectToRoute('cart_index');
    }
    #[Route('/validate', name: 'validate')]
    public function validate(Request $request, SessionInterface $session, EntityManagerInterface $em, ArticleRepository $articleRepository)
    {
        $panier = $session->get('panier', []);

        $total = 0;

        $invoice = new Invoice();
        $invoice->setUserId($this->getUser());
        $invoice->setDateDeTransaction(new \DateTimeImmutable());
        $invoice->setVilleDeFacturation('VotreVille');
        $invoice->setZipcode('12345');

        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($panier as $id => $quantite) {
                $article = $articleRepository->find($id);

                if ($article) {
                    $stock = $em->getRepository(Stock::class)->findOneBy(['article' => $article]);

                    if ($stock && $stock->getNbArticle() >= $quantite) {
                        $newStock = $stock->getNbArticle() - $quantite;

                        $query = $em->createQuery('UPDATE App\Entity\Stock s SET s.nb_article = :newStock WHERE s.article = :article');
                        $query->setParameter('newStock', $newStock);
                        $query->setParameter('article', $article);
                        $query->execute();

                        $total += $article->getPrix() * $quantite;
                        unset($panier[$id]);
                    } else {
                        unset($panier[$id]);
                        $this->addFlash('error', 'L\'article ' . $article->getNom() . ' n\'est plus disponible en stock.');
                    }
                }
            }

            $invoice->setMontant($total);

            $em->persist($invoice);
            $em->flush();

            $session->set('panier', $panier);

            $this->addFlash('success', 'Le panier a été validé avec succès.');

            return $this->redirectToRoute('invoices_index');
        }

        return $this->render('cart/invoice_form.html.twig', [
            'form' => $form->createView(),
            'data' => $panier,
            'total' => $total,
        ]);
    }
}