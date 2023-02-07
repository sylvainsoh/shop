<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('neame')->setLabel("Nom"),
            SlugField::new('slug')->setTargetFieldName("neame")->hideOnIndex(),
            TextEditorField::new('description')->setLabel("Desciption"),
            TextEditorField::new('moreInformations')->setLabel("Informations supplémentaire")->hideOnIndex(),
            MoneyField::new('price')->setCurrency("XOF")->setLabel("Prix"),
            IntegerField::new('quantity')->setLabel("Quantité"),
            TextField::new('tags'),
            BooleanField::new("isBestSeller")->setLabel("Meilleure vente ? "),
            BooleanField::new("isNewArrival")->setLabel("Nouveauté ? "),
            BooleanField::new("isFeatured")->setLabel("Mettre en vedette ?"),
            BooleanField::new("isSpecialOffer")->setLabel("Offre spéciale"),
            AssociationField::new('category')->setLabel("Catégorie"),
            ImageField::new('image')->setBasePath('/assets/uploads/products/')->setLabel("Photo")
                ->setUploadDir('/public/assets/uploads/products')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),

        ];
    }

}
