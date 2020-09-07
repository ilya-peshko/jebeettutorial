<?php

namespace App\Controller\API;

use App\Entity\Resume;
use App\Entity\User\User;
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
 * Class ResumeController
 * @package App\Controller\API
 */
class ResumeController extends AbstractController
{
    /**
     * List all resumes by user
     *
     * @Route("/api/user/{id}/resume/list/", name="nelmio_api_resume", methods={"GET"})
     * @SWG\Get(
     *     description="Returns resumes by user",
     *     produces={"text/html"},
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns the resumes of an user",
     *     @SWG\Schema(
     *         @SWG\Items(ref=@Model(type=Resume::class, groups={"resume"}))
     *     )
     * )
     * @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     type="integer",
     *     description="Page"
     * )
     * @SWG\Tag(name="Resumes")
     * @Security(name="Bearer")
     *
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param User $user
     * @ParamConverter("user", class="App:User\User")
     *
     * @return Response
     */
    public function list(
        PaginatorInterface $paginator,
        User $user,
        Request $request
    ): Response {
        $resumes = $paginator->paginate(
            $this->getDoctrine()
                ->getRepository(Resume::class)
                ->getAllResumesByUser($user, $request->get('query')),
            $request->get('page'),
            $this->getParameter('max_items_on_page')
        );

        return $this->render('api/api_resume_list.html.twig', [
            'resumes'  => $resumes,
        ]);
    }
}
