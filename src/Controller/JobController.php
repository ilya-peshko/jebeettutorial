<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Job;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
/**
 * Class JobController
 * @package App\Controller
 */
class JobController extends AbstractController
{
    /**
     * @Route("/", name="job.list")
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function list(EntityManagerInterface $em): Response
    {
        $categories = $em->getRepository(Category::class)->findWithActiveJobs();

        return $this->render('job/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("job/{id}", name="job.show", methods="GET", requirements={"id" = "\d+"})
     * @Entity("job", expr="repository.findActiveJob(id)")
     * @param Job $job
     *
     * @return Response
     */
    public function show(Job $job): Response
    {
        return $this->render('job/show.html.twig', [
            'job' => $job,
        ]);
    }
}
