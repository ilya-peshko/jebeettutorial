<?php

namespace App\Controller\API;

use App\Entity\User\User;
use App\Entity\JobApplication;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;

/**
 * Class JobApplicationController
 * @package App\Controller\API
 */
class JobApplicationController extends AbstractController
{
    /**
     * @Route(
     *     "/{_locale<en|ru>}/api/user/{id}/job-application/responses",
     *     name="nelmio_api_job_application_responses",
     *     methods="GET"
     * )
     * @SWG\Get(
     *     description="Returns responses for the company",
     *     produces={"text/html"},
     *     consumes={"text/html"}
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns responses for the company",
     *     @SWG\Schema(
     *         @SWG\Items(ref=@Model(type=JobApplication::class, groups={"job_application"}))
     *     )
     * )
     * @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     type="integer",
     *     description="Page"
     * )
     * @SWG\Tag(name="Job applications")
     * @Security(name="Bearer")
     *
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param User $user
     * @ParamConverter("user", class="App:User\User")
     *
     * @return Response
     */
    public function responses(Request $request, PaginatorInterface $paginator, User $user): Response
    {
        $jobApplications = $paginator->paginate(
            $this->getDoctrine()
                ->getRepository(JobApplication::class)
                ->getJobApplicationsByCompany($user->getCompany()),
            $request->get('page'),
            $this->getParameter('max_items_on_page')
        );

        return $this->render('api/api_job_applications.html.twig', [
            'jobApplications' => $jobApplications,
        ]);
    }
}
