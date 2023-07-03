<?php

namespace App\Controller\Admin;

use App\Entity\Config;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ConfigCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Config::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('clientName')->setLabel("Nom du site"),
            TextField::new('contact')->setLabel("Contact"),
            EmailField::new('email')->setLabel("Email"),
            TextField::new('adresse')->setLabel("Adresse"),
            ImageField::new('logo')->setBasePath('/assets/images/')->setLabel("Logo")
                ->setUploadDir('/public/assets/images/')
                ->setUploadedFileNamePattern('logo.[extension]')
                ->setRequired(false)
        ];
    }

}
