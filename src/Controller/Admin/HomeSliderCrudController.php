<?php

namespace App\Controller\Admin;

use App\Entity\HomeSlider;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HomeSliderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return HomeSlider::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title')->setLabel("Titre"),
            TextField::new('description'),
            TextField::new('buttonMessage')->setLabel("Intitulé du bouton"),
            TextField::new('buttonUrl')->setLabel("Lien du bouton"),
            ImageField::new('image')->setBasePath('/assets/uploads/slider/')->setLabel("Photo")
                ->setUploadDir('/public/assets/uploads/slider')
                ->setUploadedFileNamePattern('[randomhash].[extension]')->setRequired(false),
            BooleanField::new('isDisplayed')->setLabel("Affiché ?")
        ];
    }
}
