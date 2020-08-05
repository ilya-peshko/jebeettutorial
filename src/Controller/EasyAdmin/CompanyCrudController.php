<?php

namespace App\Controller\EasyAdmin;

use App\Entity\Company;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * Class CompanyCrudController
 * @package App\Controller\EasyAdmin
 */
class CompanyCrudController extends AbstractCrudController
{
    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Company::class;
    }

    /**
     * @param Crud $crud
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Company')
            ->setEntityLabelInPlural('Companies')
            ->setEntityPermission('ROLE_ADMIN')
            ->setPageTitle('index', '%entity_label_plural% listing')
            ->setPaginatorPageSize($this->getParameter('max_items_on_page'));
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'Id')->onlyOnIndex(),
            TextField::new('name', 'Name'),
            TextField::new('address', 'Address'),
            TelephoneField::new('phone', 'Phone'),
            AssociationField::new('user', 'User'),
            ImageField::new('imageFile')->onlyOnForms(),
            DateTimeField::new('createdAt')->onlyOnDetail(),
        ];
    }
}
