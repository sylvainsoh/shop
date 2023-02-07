<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\HomeSliderRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $repoProduct, HomeSliderRepository $homeSliderRepository): Response
    {
        $sliders=$homeSliderRepository->findOneBy(['isDisplayed'=>true]);

        $products=$repoProduct->findAll();
        $productBestSeller = $repoProduct->findByIsBestSeller(1);
        $productSpecialOffer = $repoProduct->findByIsSpecialOffer(1);
        $productNewArrival = $repoProduct->findByIsNewArrival(1);
        $productFeatured= $repoProduct->findByIsFeatured(1);

        return $this->render('home/index.html.twig', [
            'products'=>$products,
            'productBestSeller'=>$productBestSeller,
            'productSpecialOffer'=>$productSpecialOffer,
            'productNewArrival'=>$productNewArrival,
            'productFeatured'=>$productFeatured,
            'homeSlider'=>$sliders
        ]);
    }

    #[Route('/product/{slug}', name: 'product_details')]
    public function show(?Product $product) : Response {
        if (!$product){
           return $this->redirectToRoute('home');
        }

        return $this->render("home/single_product.html.twig",
        [
            'product'=>$product,
        ]);
    }
}
