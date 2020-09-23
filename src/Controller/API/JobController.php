<?php

namespace App\Controller\API;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobController extends AbstractController
{
    /**
     * Lists all job entities.
     *
     * @Route("/{_locale<en|ru>}/api/categories/activejobs/", name="api_activejobs", methods={"GET"})
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function list(EntityManagerInterface $em, Request $request): Response
    {
        $categories = $em->getRepository(Category::class)->findWithActiveJobs();

        return $this->render('api/api_job_list.html.twig', [
            'categories'  => $categories,
        ]);
    }
}