<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Entity\Product;
use App\Entity\SearchProduct;
use App\Form\SearchProductType;
use App\Repository\ConfigRepository;
use App\Repository\HomeSliderRepository;
use App\Repository\NewsletterRepository;
use App\Repository\ProductRepository;
use PhpParser\Node\Scalar\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private RequestStack $requestStack,
    )
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $repoProduct, HomeSliderRepository $homeSliderRepository, ConfigRepository $configRepository): Response
    {
        $sliders = $homeSliderRepository->findOneBy(['isDisplayed' => true]);

        $products = $repoProduct->findAll();
        $productBestSeller = $repoProduct->findByIsBestSeller(1);
        $productSpecialOffer = $repoProduct->findByIsSpecialOffer(1);
        $productNewArrival = $repoProduct->findByIsNewArrival(1);
        $productFeatured = $repoProduct->findByIsFeatured(1);


        $session = $this->requestStack->getSession();

        $config = $configRepository->findAll();
        $session->set('config', $config[0]);

        return $this->render('home/index.html.twig', [
            'products' => $products,
            'productBestSeller' => $productBestSeller,
            'productSpecialOffer' => $productSpecialOffer,
            'productNewArrival' => $productNewArrival,
            'productFeatured' => $productFeatured,
            'homeSlider' => $sliders
        ]);
    }

    #[Route('/search', name: 'search')]
    public function search(ProductRepository $repoProduct, Request $request): Response
    {
        $products = $repoProduct->findAll();
        $searchCharacter = $request->get('search');
        $search = new SearchProduct();

        $form = $this->createForm(SearchProductType::class, $search);

        if ($searchCharacter) {
            $products = $repoProduct->findWithSearch($search, $searchCharacter);
        }

        return $this->render('home/shop.html.twig', [
            'products' => $products,
            'search' => $form->createView()
        ]);
    }

    #[Route('/suscribeToNewsLetter', name: 'suscribeToNewsLetter')]
    public function suscribeToNewsLetter(Request $request, NewsletterRepository $newsletterRepository): Response
    {
        $email = $request->get('email');
        if ($email) {
            $newsletter = new Newsletter();
            $newsletter->setEmail($email);
            $newsletterRepository->save($newsletter, true);
            $this->addFlash("new_newsletter", "Votre abonnement a été enrégister avec succès");
        }
        return $this->redirectToRoute('app_home');
    }

    #[Route('/product/{slug}', name: 'product_details')]
    public function show(?Product $product): Response
    {
        if (!$product) {
            return $this->redirectToRoute('home');
        }

        return $this->render("home/single_product.html.twig",
            [
                'product' => $product,
            ]);
    }

    #[Route('/shop', name: 'shop')]
    public function shop(ProductRepository $repoProduct, Request $request): Response
    {
        $products = $repoProduct->findAll();

        $search = new SearchProduct();
        $form = $this->createForm(SearchProductType::class, $search);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $products = $repoProduct->findWithSearch($search);
        }

        return $this->render('home/shop.html.twig', [
            'products' => $products,
            'search' => $form->createView()
        ]);
    }
    #[Route("/whatsapp-chat/{productSlug}", name: 'whatsapp_chat')]
    public function startChat(string $productSlug): Response
    {
        $contact=$this->requestStack->getSession()->get('config')->getContact();
        $productUrl=$_ENV['MAIN_DOMAIN']."/product/".$productSlug;

        $whatsappUrl = 'https://api.whatsapp.com/send?phone='.$contact.'&text=' . rawurlencode('🛒 Je voudrais acheter ce produit -> ' . $productUrl);

        // Redirigez l'utilisateur vers l'URL WhatsApp
        return $this->redirect($whatsappUrl);
    }
}
