<?php

namespace App\Services;



use App\Entity\Order;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class StockManagerService
{
private $manager;
private $repoProduct;

public function __construct(EntityManagerInterface $manager, ProductRepository $repoProduct)
{
    $this->manager=$manager;
    $this->repoProduct=$repoProduct;
}

public function destock(Order $order){
    $orderDetails=$order->getOrderDetails()->getValues();
    foreach ($orderDetails as $key => $details){
        $product=$this->repoProduct->findByNeame($details->getProductName());
        $newQuantity=$product[0]->getQuantity() - $details->getQuantity();
        $product[0]->setQuantity($newQuantity);
        $this->manager->flush();
    }
}
}