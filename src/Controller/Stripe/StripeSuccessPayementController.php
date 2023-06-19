<?php

namespace App\Controller\Stripe;

use App\Entity\Order;
use App\Services\CartService;
use App\Services\StockManagerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeSuccessPayementController extends AbstractController
{
    #[Route('/stripe-success-payement/{StripeCheckoutSessionId}', name: 'app_stripe_success_payement')]
    public function index(?Order $order, CartService $cartService, EntityManagerInterface $manager, StockManagerService $stockManager): Response
    {
       if (!$order || $order->getUser()!==$this->getUser()){
           return $this->redirectToRoute('app_home');
       }

       if (!$order->isIsPaid()){
           // Commande payée
           $order->setIsPaid(true);
           // Désotckage
           $stockManager->destock($order);
           $manager->flush();
           $cartService->deleteCart();
           // Email au client pour confirmation
       }

        return $this->render('stripe_success_payement/index.html.twig', [
            'order' => $order,
        ]);
    }
}
