<?php

namespace App\Controller\Cart;

use App\Services\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    #[Route('/cart', name: 'cart')]
    public function index(CartService $cartService): Response
    {
        $cart=$cartService->getFullCart();
        if (!isset($cart['products'])){
            return $this->redirectToRoute('app_home');
        }
        return $this->render('cart/index.html.twig', [
            'cart' =>$cart,
        ]);
    }

    #[Route('/cart/add/{id}' , name: 'cart_add')]
    public function addToCart($id, CartService $cartService) : Response {
        $cartService->addToCart($id);
        return $this->redirectToRoute("cart");
    }
    #[Route('/cart/delete/{id}',name: "cart_delete")]
    public function deleteFromCart($id, CartService $cartService) : Response {
        $cartService->deleteFromCart($id);
        return $this->redirectToRoute("cart");
    }
    #[Route('/cart/delete-all/{id}',name: "cart_remove_product")]
    public function deleteAllToCart($id, CartService $cartService) : Response {
        $cartService->deleteAllToCart($id);
        return $this->redirectToRoute("cart");
    }
}
