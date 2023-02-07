<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
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
            TextField::new('user.FullName', 'Client'),
            TextField::new('carrierName', 'Livreur'),
            MoneyField::new('CarrierPrice', 'Coût de livraison')->setCurrency('XOF'),
            MoneyField::new('subTotal', 'Sous total')->setCurrency('XOF'),
            MoneyField::new('taxe', 'TVA')->setCurrency('XOF'),
            MoneyField::new('subTotalTTC', 'Sous total TTC')->setCurrency('XOF'),
            BooleanField::new('isPaid', 'Paiement effectué ?')
        ];
    }

}
