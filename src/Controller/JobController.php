<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Job;
use App\Entity\Traits\FormTrait;
use App\Entity\User\User;
use App\Form\JobType;
use App\Service\JobHistoryService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
    use FormTrait;

    /**
     * Lists all job entities.
     *
     * @Route("/", name="job_list", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param JobHistoryService $jobHistoryService
     *
     * @return Response
     */
    public function list(EntityManagerInterface $em, JobHistoryService $jobHistoryService, Request $request): Response
    {
        $search = null;
        $form = $this->createSearchForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $request->request->get('form')['Request'];
        }

        $categories = $em->getRepository(Category::class)->findWithActiveJobs($search);

        return $this->render('job/list.html.twig', [
            'categories'  => $categories,
            'historyJobs' => $jobHistoryService->getJobs(),
            'searchForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("job/{id}", name="job_show", methods="GET", requirements={"id" = "\d+"})
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

        $deleteForm = $this->createDeleteForm($job);

        return $this->render('job/show.html.twig', [
            'job'              => $job,
            'hasControlAccess' => true,
            'deleteForm'       => $deleteForm->createView(),
        ]);
    }

    /**
     * @Route("/job/create", name="job_create", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @IsGranted("ROLE_EMPLOYER")
     *
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted(
            'ROLE_EMPLOYER',
            null,
            'User tried to access a page'
        );

        $job  = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var User $user */
            $user = $this->getUser();
            $job->setCompany($user->getCompany());

            $em->persist($job);
            $em->flush();

            return $this->redirectToRoute(
                'job_list'
            );
        }

        return $this->render('job/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/job/{id}/edit", name="job_edit", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param Job $job
     * @param EntityManagerInterface $em
     * @IsGranted("ROLE_EMPLOYER")
     *
     * @return Response
     */
    public function edit(Request $request, Job $job, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted(
            'ROLE_EMPLOYER',
            null,
            'User tried to access a page'
        );

        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('job_list');
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
            ->setAction($this->generateUrl('job_delete', ['id' => $job->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @Route("job/{id}/edit/delete", name="job_delete", methods="DELETE")
     *
     * @param Request $request
     * @param Job $job
     * @param EntityManagerInterface $em
     * @IsGranted("ROLE_EMPLOYER")
     *
     * @return Response
     */
    public function delete(Request $request, Job $job, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted(
            'ROLE_EMPLOYER',
            null,
            'User tried to access a page'
        );

        $form = $this->createDeleteForm($job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($job);
            $em->flush();
        }

        return $this->redirectToRoute('job_list');
    }
}
