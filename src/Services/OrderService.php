<?php

namespace App\Services;

use App\Entity\Cart;
use App\Entity\CartDetails;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class OrderService
{
    private $manager;
    private $productRepository;

    public function __construct(EntityManagerInterface $manager, ProductRepository $productRepository)
    {
        $this->manager = $manager;
        $this->productRepository = $productRepository;
    }

    public function createOrder(Cart $cart)
    {
        $order = new Order();

        $order->setReference($cart->getReference())
            ->setCarrierName($cart->getCarrierName())
            ->setCarrierPrice($cart->getCarrierPrice()/100)
            ->setFullName($cart->getFullname())
            ->setDeliveryAddress($cart->getDeliveryAddress())
            ->setMoreInformations($cart->getMoreInformations())
            ->setQuantity($cart->getQuantity())
            ->setSubTotal($cart->getSubTotal()/100)
            ->setTaxe($cart->getTaxe()/100)
            ->setSubTotalTTC($cart->getSubTotalTTC()/100)
            ->setUser($cart->getUser())
            ->setCreatedAt(new \DateTimeImmutable());

        $this->manager->persist($order);

        $products = $cart->getCartDetails()->getValues();
        foreach ($products as $cart_product) {
            $orderDtails = new OrderDetails();
            $orderDtails->setOrders($order)
                ->setProductName($cart_product->getProductName())
                ->setProductPrice($cart_product->getProductPrice())
                ->setQuantity($cart_product->getQuantity())
                ->setSubTotal($cart_product->getSubTotal())
                ->setSubTotalTTC($cart_product->getSubTotalTTC())
                ->setTaxe(0);
            $this->manager->persist($orderDtails);
        }
        $this->manager->flush();
        return $order;
    }

    public function saveCart($data, $user)
    {
        $cart = new Cart();
        $reference = $this->generateUuid();
        $address = $data['checkout']['address'];
        $carrier = $data['checkout']['carrier'];
        $information = $data['checkout']['information'];

        $cart->setReference($reference)
            ->setCarrierName($carrier->getName())
            ->setCarrierPrice($carrier->getPrice()/100)
            ->setFullName($address->getFullname())
            ->setDeliveryAddress($address)
            ->setMoreInformations($information)
            ->setQuantity($data['data']['quantity_cart'])
            ->setSubTotal($data['data']['subTotal'])
            ->setTaxe(round($data['data']['taxe'], 2))
            ->setSubTotalTTC(($data['data']['subTotal'] + $carrier->getPrice() / 100), 2)
            ->setUser($user)
            ->setCreatedAt(new \DateTimeImmutable());
        $this->manager->persist($cart);

        $cart_details_array = [];
        foreach ($data['products'] as $products) {

            $cartDetails = new CartDetails();

            $subtotal = $products['quantity'] * $products['product']->getPrice() / 100;

            $cartDetails->setCarts($cart)
                ->setProductName($products['product']->getNeame())
                ->setProductPrice($products['product']->getPrice() / 100)
                ->setQuantity($products['quantity'])
                ->setSubTotal($subtotal)
                ->setSubTotalTTC($subtotal)
                ->setTaxe(0);
            $this->manager->persist($cartDetails);
            $cart_details_array[] = $cartDetails;
        }
        $this->manager->flush();

        return $reference;
    }

    public function generateUuid()
    {
        mt_srand((double)microtime()) * 100000;

        $charid = strtoupper(md5(uniqid(rand(), true)));

        $hyphen = chr(45);

        $uuid = ""
            . substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12) . $hyphen;
        return $uuid;
    }

    public function getLineItems(Cart $cart)
    {
        $cartDetails=$cart->getCartDetails();

        $line_items=[];
        foreach ($cartDetails as $detail){
            $product=$this->productRepository->findOneByNeame($detail->getProductName());
            $line_items[]=[
                'price_data'=>[
                    'currency'=>'xof',
                    'unit_amount'=>$product->getPrice()/100,
                    'product_data'=>[
                        'name'=>$product->getNeame(),
                        'images'=>[$_ENV['MAIN_DOMAIN'].'/uploads/products/'.$product->getImage()],
                    ]
                ],
                'quantity'=>$detail->getQuantity(),
            ];
            // Taxe
         /*
            $line_items[]=[
                'price_data'=>[
                    'currency'=>'xof',
                    'unit_amount'=>$cart->getTaxe(),
                    'product_data'=>[
                        'name'=>"Tva (20%)",
                        'images'=>'
                    ]
                ],
                'quantity'=>1,
            ];
            */
        }
        // informations de facturation de lalivraison
        $line_items[]=[
            'price_data'=>[
                'currency'=>'xof',
                'unit_amount'=>$cart->getCarrierPrice()/100,
                'product_data'=>[
                    'name'=>'Livraison('.($cart->getCarrierName()).')',
                        'images'=>[$_ENV['MAIN_DOMAIN'].'/uploads/products/'],
                    ]
            ],
            'quantity'=>1,
        ];

        return $line_items;
    }

}