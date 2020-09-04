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
     *     "/category/{slug}/",
     *     name="category_show",
     *     methods={"GET"},
     * )
     *
     * @param Category $category
     * @param JobHistoryService $jobHistoryService
     *
     * @return Response
     */
    public function show(Category $category, JobHistoryService $jobHistoryService): Response
    {
        return $this->render('category/show.html.twig', [
            'category'    => $category,
            'historyJobs' => $jobHistoryService->getJobs(),
        ]);
    }
}
