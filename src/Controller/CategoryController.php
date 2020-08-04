<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Job;
use App\Entity\Traits\FormTrait;
use App\Service\JobHistoryService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 * @package App\Controller
 */
class CategoryController extends AbstractController
{
    use FormTrait;

    /**
     * Finds and displays a category entity.
     *
     * @Route(
     *     "/category/{slug}/{page}",
     *     name="category_show",
     *     methods={"GET", "POST"},
     *     defaults={"page": 1},
     *     requirements={"page" = "\d+"}
     * )
     *
     * @param Category $category
     * @param int $page
     * @param PaginatorInterface $paginator
     * @param JobHistoryService $jobHistoryService
     * @param Request $request
     *
     * @return Response
     */
    public function show(
        Category $category,
        int $page,
        PaginatorInterface $paginator,
        JobHistoryService $jobHistoryService,
        Request $request
    ) : Response {

        $search = null;
        $form = $this->createSearchForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $request->request->get('form')['Request'];
        }

        $activeJobs = $paginator->paginate(
            $this->getDoctrine()
                ->getRepository(Job::class)
                ->getActiveJobsByCategoryQuery($category, $search),
            $page,
            $this->getParameter('max_items_on_page')
        );

        return $this->render('category/show.html.twig', [
            'category'    => $category,
            'activeJobs'  => $activeJobs,
            'historyJobs' => $jobHistoryService->getJobs(),
            'searchForm'  => $form->createView()
        ]);
    }
}