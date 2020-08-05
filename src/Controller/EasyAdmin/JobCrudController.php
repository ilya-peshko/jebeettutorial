<?php

namespace App\Controller\EasyAdmin;

use App\Entity\Job;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

/**
 * Class JobCrudController
 * @package App\Controller\EasyAdmin
 */
class JobCrudController extends AbstractCrudController
{
    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Job::class;
    }

    /**
     * @param Crud $crud
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Job')
            ->setEntityLabelInPlural('Jobs')
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
            ChoiceField::new('type','Type')->setChoices(
                [
                    'Freelance' => Job::FREELANCE_TYPE,
                    'Full time' => Job::FULL_TIME_TYPE,
                    'Part time' => Job::PART_TIME_TYPE
                ]),
            AssociationField::new('category', 'Category name'),
            AssociationField::new('company', 'Company'),
            TextField::new('title'),
            TextField::new('position', 'Position'),
            TextareaField::new('description'),
            UrlField::new('url', 'Url'),
            CountryField::new('location', 'Location'),
            BooleanField::new('public', 'Public'),
            BooleanField::new('activated', 'Active'),
            ImageField::new('imageFile')->onlyOnForms(),
            DateTimeField::new('createdAt')->onlyOnDetail(),
            DateTimeField::new('updatedAt')->onlyOnIndex(),
            EmailField::new('email', 'Email'),
        ];
    }
}
