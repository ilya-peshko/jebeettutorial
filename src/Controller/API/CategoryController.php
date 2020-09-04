<?php

namespace App\Controller\API;

use App\Entity\Category;
use App\Entity\Job;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;

class CategoryController extends AbstractController
{
    /**
     * Finds and displays a category entity.
     *
     * @Route(
     *     "api/category/{slug}",
     *     name="nelmio_api_category",
     *     methods={"GET"},
     * )
     * @SWG\Get(
     *     description="Return jobs by category",
     *     produces={"text/html"},
     *     consumes={"text/html"}
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns jobs by category",
     *     @SWG\Schema(
     *         @SWG\Items(ref=@Model(type=Job::class, groups={"api_category_job"}))
     *     )
     * )
     * @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     type="integer",
     *     description="Page"
     * )
     * @SWG\Tag(name="Category")
     * @Security(name="Bearer")
     *
     * @param Category $category
     * @param PaginatorInterface $paginator
     * @param Request $request
     *
     * @return Response
     */
    public function list(
        Category $category,
        PaginatorInterface $paginator,
        Request $request
    ) : Response {

        $activeJobs = $paginator->paginate(
            $this->getDoctrine()
                ->getRepository(Job::class)
                ->getActiveJobsByCategoryQuery($category, $request->get('query')),
            $request->get('page'),
            $this->getParameter('max_items_on_page')
        );

        return $this->render('api/api_category_jobs.html.twig', [
            'category'    => $category,
            'activeJobs'  => $activeJobs,
        ]);
    }
}
