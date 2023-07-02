<?php

namespace App\Controller\Account;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findBy(
            [
                'user' => $this->getUser()
            ],
            [
                'id' => 'DESC'
            ]);
        return $this->render('account/index.html.twig', [
            'orders' => $orders
        ]);
    }

    #[Route('/order/{id}', name: 'app_account_show_order')]
    public function show(?Order $order): Response
    {
        if (!$order || $order->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_account');
        }
        if (!$order->isIsPaid()) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('account/detail_order.html.twig', [
            'order' => $order
        ]);
    }
}
