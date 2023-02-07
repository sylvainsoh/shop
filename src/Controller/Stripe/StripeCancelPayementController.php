<?php

namespace App\Controller\Stripe;

use App\Entity\Order;
use App\Services\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeCancelPayementController extends AbstractController
{
    #[Route('/stripe-cancel-payement/{StripeCheckoutSessionId}', name: 'app_stripe_cancel_payement')]
    public function index(?Order $order, CartService $cartService, EntityManagerInterface $manager): Response
    {
        if (!$order || $order->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        return $this->render('stripe_cancel_payement/index.html.twig', [
            'order' => $order
            ]);
    }
}
