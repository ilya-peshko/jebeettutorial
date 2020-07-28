<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Job;
use App\Form\JobType;
use App\Service\JobHistoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
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
     * Lists all job entities.
     *
     * @Route("/", name="job.list", methods="GET")
     *
     * @param EntityManagerInterface $em
     * @param JobHistoryService $jobHistoryService
     *
     * @return Response
     */
    public function list(EntityManagerInterface $em, JobHistoryService $jobHistoryService): Response
    {
        $categories = $em->getRepository(Category::class)->findWithActiveJobs();

        return $this->render('job/list.html.twig', [
            'categories'  => $categories,
            'historyJobs' => $jobHistoryService->getJobs(),
        ]);
    }

    /**
     * @Route("job/{id}", name="job.show", methods="GET", requirements={"id" = "\d+"})
     *
     * @Entity("job", expr="repository.findActiveJob(id)")
     *
     * @param Job $job
     * @param JobHistoryService $jobHistoryService
     *
     * @return Response
     */
    public function show(Job $job, JobHistoryService $jobHistoryService): Response
    {
        $jobHistoryService->addJob($job);
        $deleteForm  = $this->createDeleteForm($job);

        return $this->render('job/show.html.twig', [
            'job'              => $job,
            'hasControlAccess' => true,
            'deleteForm'       => $deleteForm->createView(),
        ]);
    }

    /**
     * @Route("/job/create", name="job.create", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $job  = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($job);
            $em->flush();

            return $this->redirectToRoute(
                'job.list'
            );
        }

        return $this->render('job/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/job/{id}/edit", name="job.edit", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param Job $job
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function edit(Request $request, Job $job, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute(
                'job.list'
            );
        }
        return $this->render('job/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a job entity.
     *
     * @param Job $job
     *
     * @return FormInterface
     */
    private function createDeleteForm(Job $job): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('job.delete', ['id' => $job->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @Route("job/{id}/edit/delete", name="job.delete", methods="DELETE")
     *
     * @param Request $request
     * @param Job $job
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function delete(Request $request, Job $job, EntityManagerInterface $em): Response
    {
        $form = $this->createDeleteForm($job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($job);
            $em->flush();
        }

        return $this->redirectToRoute('job.list');
    }
}
