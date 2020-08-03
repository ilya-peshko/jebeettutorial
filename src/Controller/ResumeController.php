<?php

namespace App\Controller;

use App\Entity\Job;
use App\Entity\JobApplication;
use App\Entity\Resume;
use App\Entity\User\User;
use App\Form\ResumeType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResumeController extends AbstractController
{
    /**
     * @Route(
     *     "/resume/list/{page}",
     *     name="resume_list",
     *     methods="GET",
     *     defaults={"page": 1},
     *     requirements={"page" = "\d+"}
     * )
     * @param int $page
     * @param PaginatorInterface $paginator
     * @IsGranted("ROLE_APPLICANT")
     *
     * @return Response
     */
    public function list(PaginatorInterface $paginator, int $page): Response
    {
        $this->denyAccessUnlessGranted(
            'ROLE_APPLICANT',
            null,
            'User tried to access a page'
        );

        /** @var User $user */
        $user = $this->getUser();
        $resumes = $paginator->paginate(
            $this->getDoctrine()->getRepository(Resume::class)->getAllResumesByUser($user),
            $page,
            $this->getParameter('max_items_on_page')
        );

        return $this->render('resume/list.html.twig', [
            'resumes'  => $resumes,
        ]);
    }

    /**
     * @Route("/resume/create", name="resume_create", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @IsGranted("ROLE_APPLICANT")
     *
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted(
            'ROLE_APPLICANT',
            null,
            'User tried to access a page'
        );

        $resume = new Resume();
        $form = $this->createForm(ResumeType::class, $resume);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var User $user */
            $user = $this->getUser();
            $resume->setUser($user);

            $em->persist($resume);
            $em->flush();

            return $this->redirectToRoute(
                'resume_list'
            );
        }

        return $this->render('resume/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("resume/{id}", name="resume_show", methods="GET", requirements={"id" = "\d+"})
     *
     * @Entity("resume", expr="repository.findResumeById(id)")
     *
     * @param Resume $resume
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function show(Request $request, Resume $resume, EntityManagerInterface $em): Response
    {
        $deleteForm = $this->createDeleteForm($resume);

        /** @var User $user */
        $user = $this->getUser();

        if ($request->query->get('id')) {

            /** @var JobApplication $jobApplication */
            $jobApplication = $this->getDoctrine()
                ->getRepository(JobApplication::class)
                ->find($request->query->get('id'));

            if ($jobApplication && ($jobApplication->getJob()->getCompany()->getUser() === $user)){
                $jobApplication->setViewed(true);

                $em->persist($jobApplication);
                $em->flush();
            }
        }

        return $this->render('resume/show.html.twig', [
            'resume'     => $resume,
            'deleteForm' => $deleteForm->createView(),
        ]);
    }

    /**
     * @param Resume $resume
     *
     * @return FormInterface
     */
    private function createDeleteForm(Resume $resume): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('resume_delete', ['id' => $resume->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @Route("resume/{id}/edit/delete", name="resume_delete", methods="DELETE")
     *
     * @param Request $request
     * @param Resume $resume
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function delete(Request $request, Resume $resume, EntityManagerInterface $em): Response
    {
        $form = $this->createDeleteForm($resume);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($resume);
            $em->flush();
        }

        return $this->redirectToRoute('resume_list');
    }

    /**
     * @Route("/resume/{id}/edit", name="resume_edit", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param Resume $resume
     * @param EntityManagerInterface $em
     * @IsGranted("ROLE_APPLICANT")
     *
     * @return Response
     */
    public function edit(Request $request, Resume $resume, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted(
            'ROLE_APPLICANT',
            null,
            'User tried to access a page'
        );

        $form = $this->createForm(ResumeType::class, $resume);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('resume_list');
        }
        return $this->render('resume/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/job/{id}/resume/choise", name="resume_choice")
     * @param Request $request
     * @IsGranted("ROLE_APPLICANT")
     *
     * @return Response
     */
    public function choice(Request $request): Response
    {
        $this->denyAccessUnlessGranted(
            'ROLE_APPLICANT',
            null,
            'User tried to access a page'
        );

        /** @var User $user */
        $user    = $this->getUser();
        $resumes = $this->getDoctrine()->getRepository(Resume::class)->getAllResumesByUser($user);

        return $this->render('resume/choice.html.twig', [
            'resumes' => $resumes,
            'job_id'  => $request->attributes->get('id')
        ]);
    }

    /**
     * @Route("/{job_id}/{resume_id}/confirm", name="resume_confirm")
     * @Entity("job", expr="repository.findActiveJob(job_id)")
     * @Entity("resume", expr="repository.findResumeById(resume_id)")
     * @param EntityManagerInterface $em
     * @param Job $job
     * @param Resume $resume
     * @IsGranted("ROLE_APPLICANT")
     *
     * @return Response
     */
    public function confirm(EntityManagerInterface $em, Job $job, Resume $resume): Response
    {
        $this->denyAccessUnlessGranted(
            'ROLE_APPLICANT',
            null,
            'User tried to access a page'
        );

        $coincidence =
            $this->getDoctrine()
            ->getRepository(JobApplication::class)
            ->checkCoincidence($job, $resume);

        if (count($coincidence)) {
            throw new Exception('Resume exist for this job');
        }

        $jobApplication = new JobApplication();
        $jobApplication->setJob($job)
            ->setResume($resume);

        $em->persist($jobApplication);
        $em->flush();

        return $this->redirectToRoute('job_list');
    }
}
