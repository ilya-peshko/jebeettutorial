<?php

namespace App\Controller;


use App\Entity\Job;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class JobController
 * @package App\Controller
 */
class JobController extends AbstractController
{
    /**
     * @Route("/", name="job.list")
     *
     * @return Response
     */
    public function list(): Response
    {
        $jobs = $this->getDoctrine()->getRepository(Job::class)->findAll();

        return $this->render('job/list.html.twig', [
            'jobs' => $jobs,
        ]);
    }

    /**
     * @Route("/{id}", name="job.show")
     * @param Job $job
     * @ParamConverter("job", class="App\Entity\Job")
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
