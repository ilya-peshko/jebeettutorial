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
     *     "/{_locale<en|ru>}/job-application/{page}",
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
     *     "/{_locale<en|ru>}/job-application/responses/",
     *     name="job_application_responses",
     *     methods="GET",
     * )
     *
     * @IsGranted("ROLE_EMPLOYER")
     *
     * @return Response
     */
    public function responses(): Response
    {
        $this->denyAccessUnlessGranted(
            'ROLE_EMPLOYER',
            null,
            'User tried to access a page'
        );

        return $this->render('job_application/responses.html.twig');
    }
}
