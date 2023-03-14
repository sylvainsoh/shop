<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort([
                'id'=>'DESC'
            ]
        );
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name')->setLabel("Nom"),
            TextField::new('email')->setLabel("Email"),
            TextField::new('subject')->setLabel("Objet"),
            BooleanField::new('isRead')->setLabel("Lu ? "),
            TextEditorField::new('content')->setLabel("Contenu"),
        ];
    }

}
