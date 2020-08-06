<?php

namespace App\Controller\EasyAdmin;

use App\Entity\Company;
use App\Entity\Job;
use App\Entity\JobApplication;
use App\Entity\Resume;
use App\Entity\User\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class DashboardController
 * @package App\Controller\EaseAdmin
 */
class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();

        return $this->redirect($routeBuilder->setController(CategoryCrudController::class)->generateUrl());
    }

    /**
     * @return Dashboard
     */
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Jobeettutorial');
    }

    /**
     * @return iterable
     */
    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linktoDashboard('Dashboard', 'fa fa-home'),

            MenuItem::section('Tables'),
            MenuItem::linkToCrud('Users', 'fa fa-user', User::class)
                ->setPermission('ROLE_ADMIN'),
            MenuItem::linkToCrud('Categories', 'fa fa-list', Category::class)
                ->setPermission('ROLE_ADMIN'),
            MenuItem::linkToCrud('Companies', 'fa fa-building', Company::class)
                ->setPermission('ROLE_ADMIN'),
            MenuItem::linkToCrud('Jobs', 'fa fa-briefcase', Job::class)
                ->setPermission('ROLE_ADMIN'),
            MenuItem::linkToCrud('Resumes', 'fa fa-address-book', Resume::class)
                ->setPermission('ROLE_ADMIN'),

            MenuItem::section('Options'),
            MenuItem::linkToLogout('Logout', 'fa fa-door-open'),
            MenuItem::linktoRoute('Back to site', 'fa fa-compass', 'job_list'),
        ];
        // yield MenuItem::linkToCrud('The Label', 'icon class', EntityClass::class);
    }

    /**
     * @param UserInterface $user
     * @return UserMenu
     */
    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->setName($user->getUsername());
    }
}
