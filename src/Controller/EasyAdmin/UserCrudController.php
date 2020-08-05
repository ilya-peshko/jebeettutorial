<?php

namespace App\Controller\EasyAdmin;

use App\Entity\User\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * Class UserCrudController
 * @package App\Controller\EasyAdmin
 */
class UserCrudController extends AbstractCrudController
{
    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    /**
     * @param Crud $crud
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('User')
            ->setEntityLabelInPlural('Users')
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
            IdField::new('id')->onlyOnIndex(),
            TextField::new('username', 'Username'),
            TextField::new('password', 'Password')->onlyWhenCreating(),
            BooleanField::new('enabled', 'Is Active')->onlyOnForms(),
            EmailField::new('email', 'Email'),
            ChoiceField::new('roles', 'Roles')->setChoices(
                [
                    'Employer'  => 'ROLE_EMPLOYER',
                    'Applicant' => 'ROLE_APPLICANT',
                    'Admin'     => 'ROLE_ADMIN',
                ])
                ->allowMultipleChoices(),
        ];
    }
}
