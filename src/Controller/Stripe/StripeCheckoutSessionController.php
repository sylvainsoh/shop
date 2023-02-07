<?php

namespace App\Controller\Stripe;

use App\Entity\Cart;
use App\Entity\Order;
use App\Repository\CartRepository;
use App\Services\CartService;
use App\Services\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeCheckoutSessionController extends AbstractController
{
    #[Route('/create-checkout-session', name: 'create_checkout_session')]
    public function index(?CartRepository $cartRepository, OrderService $orderService, Request $request, EntityManagerInterface $manager): Response
    {
        $reference=$request->request->get('reference');
        $user=$this->getUser();
        $cart=$cartRepository->findOneByReference($reference);

        if (!$cart) {
            return $this->redirectToRoute('app_home');
        }

        $order=$orderService->createOrder($cart);

        Stripe::setApiKey($_ENV['STRIPE_KEY']);

        $checkout_session = Session::create([
            'customer_email'=>$user->getUserIdentifier(),
            'line_items' => $orderService->getLineItems($cart),
            'mode' => 'payment',
            'success_url' => $_ENV['MAIN_DOMAIN'] . '/stripe-success-payement/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $_ENV['MAIN_DOMAIN'] . '/stripe-cancel-payement/{CHECKOUT_SESSION_ID}',
        ]);
        $order->setStripeCheckoutSessionId($checkout_session->id);
        $manager->flush();

        return $this->redirect($checkout_session->url);
    }
}
