<?php

namespace App\Controller\Cart;

use App\Form\CheckoutType;
use App\Services\CartService;
use App\Services\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    #[Route('/checkout', name: 'app_checkout')]
    public function index(CartService $cartService, Request $request): Response
    {
        $user = $this->getUser();
        $cart = $cartService->getFullCart();

        if (!isset($cart['products'])) {
            return $this->redirectToRoute('app_home');
        }
        if (!$cart) {
            return $this->redirectToRoute('app_home');
        }
        if (empty($user->getAddresses()->getValues())) {
            $this->addFlash('checkout_message', 'Vous devez ajouter une addresse de livraison à votre compte pour poursuivre le processus ');
            return $this->redirectToRoute('app_address_new');
        }

        $form = $this->createForm(CheckoutType::class, null, ['user' => $user]);

        return $this->render('checkout/index.html.twig', [
            'cart' => $cart,
            'checkout' => $form->createView()
        ]);
    }

    #[Route('/checkout/confirm', name: 'app_checkout_confirm')]
    public function confirm(CartService $cartService, Request $request, OrderService $orderService): Response
    {
        $cart = $cartService->getFullCart();
        $user = $this->getUser();

        if (!isset($cart['products'])) {
            return $this->redirectToRoute('app_home');
        }

        if (!$cart) {
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(CheckoutType::class, null, ['user' => $user]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $data=$form->getData();
            $address=$data['address'];
            $carrier=$data['carrier'];
            $information=$data['information'];

            // Save cart
            $cart['checkout']=$data;
            $reference=$orderService->saveCart($cart, $user);

            return $this->render('checkout/confirm.html.twig', [
                'cart' => $cart,
                'address' => $address,
                'carrier' => $carrier,
                'information' => $information,
                'reference'=>$reference,
                'checkout' => $form->createView()
            ]);
        }
        return $this->redirectToRoute('app_checkout');
    }
}
