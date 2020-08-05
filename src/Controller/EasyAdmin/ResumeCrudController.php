<?php

namespace App\Controller\EasyAdmin;

use App\Entity\Resume;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * Class ResumeCrudController
 * @package App\Controller\EasyAdmin
 */
class ResumeCrudController extends AbstractCrudController
{

    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Resume::class;
    }

    /**
     * @param Crud $crud
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Resume')
            ->setEntityLabelInPlural('Resumes')
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
            AssociationField::new('user', 'Applicant'),
            TextField::new('title', 'Title'),
            TextareaField::new('aboutMe', 'About me'),
            TextField::new('name', 'Name'),
            TextField::new('surname', 'Surname'),
            TextField::new('cityOfResidence', 'City of residence'),
            DateTimeField::new('dateOfBirthday', 'Day of birthday'),
            ChoiceField::new('gender', 'Gender')->setChoices(
                [
                    'Male'   => 'Male',
                    'Female' => 'Female'
                ])
        ];
    }
}
