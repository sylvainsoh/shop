<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use App\Entity\Cart;
use App\Entity\Categories;
use App\Entity\Contact;
use App\Entity\HomeSlider;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
       // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
       return $this->redirect($adminUrlGenerator->setController(OrderCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Tableau de bord')
            ->setLocales([
                'fr' => '🇫🇷 French',
                'en' => '🇬🇧 English'
            ])
            ->generateRelativeUrls()
            ->renderContentMaximized()
            //->renderSidebarMinimized()
            ->setFaviconPath('/assets/images/favicon.png');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Accueil', 'fa fa-home');
        yield MenuItem::linkToCrud('Commandes', 'fa fa-shopping-bag',Order::class);
        yield MenuItem::linkToCrud('Paniers', 'fa fa-shopping-basket',Cart::class);
        yield MenuItem::linkToCrud('Produits', 'fa fa-shopping-cart',Product::class);
        yield MenuItem::linkToCrud('Categories', 'fa fa-list',Categories::class);
        yield MenuItem::linkToCrud('Livreur', 'fa fa-truck',Carrier::class);
        yield MenuItem::linkToCrud('Contact', 'fa fa-envelope',Contact::class);
        yield MenuItem::linkToCrud('Slider accueil', 'fa fa-images',HomeSlider::class);
        yield MenuItem::linkToCrud('Comptes', 'fa fa-user',User::class);
    }

    public function configureAssets(): Assets
    {
        return Assets::new()->addCssFile('css/admin.css');
    }
}
