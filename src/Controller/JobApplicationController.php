<?php

namespace App\Controller;

use App\Entity\JobApplication;
use App\Entity\User\User;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobApplicationController extends AbstractController
{
    /**
     * @Route(
     *     "/job-application/{page}",
     *     name="job_application_list",
     *     methods="GET",
     *     defaults={"page": 1},
     *     requirements={"page" = "\d+"}
     * )
     * @param int $page
     * @param PaginatorInterface $paginator
     *
     * @IsGranted("ROLE_APPLICANT")
     *
     * @return Response
     */
    public function list(int $page, PaginatorInterface $paginator): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $jobApplications = $paginator->paginate(
            $this->getDoctrine()
                ->getRepository(JobApplication::class)
                ->getJobApplicationsByUser($user),
            $page,
            $this->getParameter('max_items_on_page')
        );
        return $this->render('job_application/list.html.twig', [
            'jobApplications' => $jobApplications,
        ]);
    }

    /**
     * @Route(
     *     "/job-application/responses/{page}",
     *     name="job_application_responses",
     *     methods="GET",
     *     defaults={"page": 1},
     *     requirements={"page" = "\d+"}
     * )
     * @param int $page
     * @param PaginatorInterface $paginator
     *
     * @IsGranted("ROLE_EMPLOYER")
     *
     * @return Response
     */
    public function responses(int $page, PaginatorInterface $paginator): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $jobApplications = $paginator->paginate(
            $this->getDoctrine()
                ->getRepository(JobApplication::class)
                ->getJobApplicationsByCompany($user->getCompany()),
            $page,
            $this->getParameter('max_items_on_page')
        );

        return $this->render('job_application/responses.html.twig', [
            'jobApplications' => $jobApplications,
        ]);
    }
}
