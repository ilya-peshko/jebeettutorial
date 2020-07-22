<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Job;
use App\Repository\JobRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 * @package App\Controller
 */
class CategoryController extends AbstractController
{
    /**
     * @Route(
     *     "/category/{slug}/{page}",
     *     name="category.show",
     *     methods="GET",
     *     defaults={"page": 1},
     *     requirements={"page" = "\d+"}
     * )
     *
     * @param Category $category
     * @param PaginatorInterface $paginator
     * @param int $page
     *
     * @return Response
     */
    public function show(
        Category $category,
        PaginatorInterface $paginator,
        int $page
    ) : Response {
        /** @var JobRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Job::class);

        return $this->render('category/show.html.twig', [
            'category'   => $category,
            'activeJobs' => $paginator->paginate(
                $repository->getActiveJobsByCategoryQuery($category),
                $page,
                $this->getParameter('max_jobs_on_category')
            ),
        ]);
    }
}