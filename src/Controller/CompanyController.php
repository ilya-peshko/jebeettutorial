<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User\User;
use App\Form\CompanyType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    /**
     * @Route("/company/create", name="company_create", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @IsGranted("ROLE_EMPLOYER")
     *
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted(
            'ROLE_EMPLOYER',
            null,
            'User tried to access a page'
        );

        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($company);
            $em->flush();

            return $this->redirectToRoute(
                'company_show'
            );
        }

        return $this->render('company/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("company/show", name="company_show", methods="GET", requirements={"id" = "\d+"})
     *
     * @IsGranted("ROLE_EMPLOYER")
     *
     * @return Response
     */
    public function show(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $this->denyAccessUnlessGranted(
            'ROLE_EMPLOYER',
            null,
            'User tried to access a page'
        );

        /** @var Company $company */
        $company =  $this->getDoctrine()->getRepository(Company::class)->getCompanyByUser($user);

        return $this->render('company/show.html.twig', [
            'company'    => $company,
        ]);
    }

    /**
     * @Route("/company/edit", name="company_edit", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @IsGranted("ROLE_EMPLOYER")
     *
     * @return Response
     */
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted(
            'ROLE_EMPLOYER',
            null,
            'User tried to access a page'
        );
        /** @var User $user */
        $user = $this->getUser();

        /** @var Company $company */
        $company =  $this->getDoctrine()->getRepository(Company::class)->getCompanyByUser($user);

        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('company_show');
        }
        return $this->render('company/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("company/edit/delete/{id}", name="company_delete", methods="GET")
     * @Entity("company", expr="repository.find(id)")
     * @param Request $request
     * @param Company $company
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function delete(Request $request, Company $company, EntityManagerInterface $em): Response
    {
        if ($this->getUser() !== $company->getUser()) {
            throw new Exception('You not have permissions');
        }
        $em->remove($company);
        $em->flush();

        return $this->redirectToRoute('company_show');
    }
}
