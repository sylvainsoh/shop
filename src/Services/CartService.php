<?php

namespace App\Services;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;


class  CartService
{
    private ProductRepository $repoProduct;
    /**
     * @var RequestStack
     */
    private $requestStack;

    private $session;

    private $tva=0.2;

    public function __construct(RequestStack $requestStack, ProductRepository $repoProduct)
    {
        $this->requestStack = $requestStack;
        $this->repoProduct = $repoProduct;
        $this->session=$this->getSession();
    }

    public function addToCart($id)
    {
        $cart = $this->getCart();
        if (isset($cart[$id])) {
            // produit déja present dans le panier
            $cart[$id]++;
        } else {
            // produit n'est pas encore dans le panier
            $cart[$id] = 1;
        }
        $this->updateCart($cart);
    }

    public function deleteFromCart($id)
    {
        $cart = $this->getCart();
        if (isset($cart[$id])) {
            // produit déja dans le panier
            if ($cart[$id] > 1) {
                // produit existant plus d'une fois

            } else {
                unset($cart[$id]);
            }
            $this->updateCart($cart);
        }
    }

    public function deleteAllToCart($id)
    {
        $cart = $this->getCart();
        if (isset($cart[$id])) {
            unset($cart[$id]);
            $this->updateCart($cart);
        }
    }

    public function deleteCart()
    {
        $this->updateCart([]);
    }

    public function updateCart($cart)
    {
        $this->session->set('cart', $cart);
        $this->session->set('cartData', $this->getFullCart());
    }

    public function getCart()
    {
        return $this->session->get('cart', []);
    }

    public function getFullCart()
    {
        $cart = $this->getCart();

        $fullCart = [];
        $quantity_cart=0;
        $subTotal=0;
        foreach ($cart as $id => $quantity) {
            $product = $this->repoProduct->find($id);
            if ($product) {
                if ($quantity > $product->getQuantity()){
                    $quantity=$product->getQuantity();
                    $cart[$id] = $quantity;
                    $this->updateCart($cart);
                }
                $fullCart["products"][] = [
                    "quantity" => $quantity,
                    'product' => $product
                ];
                $quantity_cart+=$quantity;
                $subTotal+=$quantity*($product->getPrice()/100);
            } else {
                $this->deleteFromCart($id);
            }
        }
        $fullCart['data']=[
            'quantity_cart'=>$quantity_cart,
            'subTotal'=>$subTotal,
            'taxe'=>0
        ];
        return $fullCart;
    }
    public function getSession(){

        return $this->requestStack->getCurrentRequest()->getSession();

    }
}