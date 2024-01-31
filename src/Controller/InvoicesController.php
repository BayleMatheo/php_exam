<?php

namespace App\Controller;

use App\Repository\InvoiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/invoices', name: 'invoices_')]
class InvoicesController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(InvoiceRepository $invoiceRepository): Response
    {
        $invoices = $invoiceRepository->findBy(['user_id' => $this->getUser()]);

        return $this->render('invoices/index.html.twig', [
            'invoices' => $invoices,
        ]);
    }
}