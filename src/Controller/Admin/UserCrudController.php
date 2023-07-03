<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('email')->setLabel("Email"),
            TextField::new('username')->setLabel("Pseudo"),
            TextField::new('firstname')->setLabel("Nom"),
            TextField::new('lastname')->setLabel("Prenom"),
            BooleanField::new('isVerified')->setLabel("Compte vérifié ? "),
            TextField::new('tel')->setLabel("Contact"),
        ];
    }

}
