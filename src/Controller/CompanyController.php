<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

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
                'company_list'
            );
        }

        return $this->render('company/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Lists all company entities.
     *
     * @Route(
     *     "/company/list/{page}",
     *     name="company_list",
     *     methods="GET",
     *     defaults={"page": 1},
     *     requirements={"page" = "\d+"}
     * )
     *
     * @param EntityManagerInterface $em
     * @param int $page
     * @param PaginatorInterface $paginator
     *
     *
     * @return Response
     */
    public function list(EntityManagerInterface $em, PaginatorInterface $paginator, int $page): Response
    {
        $companies = $paginator->paginate(
            $this->getDoctrine()->getRepository(Company::class)->getAllCompanies(),
            $page,
            $this->getParameter('max_items_on_page')
        );

        return $this->render('company/list.html.twig', [
            'companies'  => $companies
        ]);
    }

    /**
     * @Route("/company/{id}/edit", name="company_edit", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param Company $company
     * @param EntityManagerInterface $em
     * @IsGranted("ROLE_EMPLOYER")
     *
     * @return Response
     */
    public function edit(Request $request, Company $company, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted(
            'ROLE_EMPLOYER',
            null,
            'User tried to access a page'
        );

        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('company_list');
        }
        return $this->render('company/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("company/{id}", name="company_show", methods="GET", requirements={"id" = "\d+"})
     *
     * @Entity("company", expr="repository.findCompanyById(id)")
     *
     * @param Company $company
     *
     * @return Response
     */
    public function show(Company $company): Response
    {
        $deleteForm = $this->createDeleteForm($company);

        return $this->render('company/show.html.twig', [
            'company'    => $company,
            'deleteForm' => $deleteForm->createView(),
        ]);
    }

    /**
     * Creates a form to delete a job entity.
     *
     * @param Company $company
     *
     * @return FormInterface
     */
    private function createDeleteForm(Company $company): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('company_delete', ['id' => $company->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @Route("company/{id}/edit/delete", name="company_delete", methods="DELETE")
     *
     * @param Request $request
     * @param Company $company
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function delete(Request $request, Company $company, EntityManagerInterface $em): Response
    {
        $form = $this->createDeleteForm($company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($company);
            $em->flush();
        }

        return $this->redirectToRoute('company_list');
    }
}
