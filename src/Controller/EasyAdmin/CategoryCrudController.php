<?php

namespace App\Controller\EasyAdmin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * Class CategoryCrudController
 * @package App\Controller\EasyAdmin
 */
class CategoryCrudController extends AbstractCrudController
{
    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    /**
     * @param Crud $crud
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Category')
            ->setEntityLabelInPlural('Categories')
            ->setEntityPermission('ROLE_ADMIN')
            ->setPageTitle('index', '%entity_label_plural% listing')
            ->setPaginatorPageSize($this->getParameter('max_items_on_page'));
    }

    /**
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'Id')->onlyOnIndex(),
            TextField::new('name', 'Category name'),
            TextField::new('slug', 'Slug')->onlyOnIndex(),
        ];
    }
}
