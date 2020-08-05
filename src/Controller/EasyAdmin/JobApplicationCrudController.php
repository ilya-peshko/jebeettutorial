<?php

namespace App\Controller\EasyAdmin;

use App\Entity\JobApplication;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

/**
 * Class JobApplicationCrudController
 * @package App\Controller\EasyAdmin
 */
class JobApplicationCrudController extends AbstractCrudController
{
    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return JobApplication::class;
    }

    /**
     * @param Crud $crud
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Job application')
            ->setEntityLabelInPlural('Job applications')
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
            AssociationField::new('job', 'Job'),
            AssociationField::new('resume', 'Resume'),
        ];
    }
}
