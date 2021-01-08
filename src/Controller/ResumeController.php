<?php

namespace App\Controller;

use App\Controller\API\BaseController;
use App\Entity\Chat;
use App\Entity\Job;
use App\Entity\JobApplication;
use App\Entity\Resume;
use App\Entity\Traits\FormTrait;
use App\Entity\User\User;
use App\Form\ResumeType;
use App\Repository\ChatRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResumeController extends BaseController
{
    use FormTrait;

    /**
     * @var ChatRepository
     */
    private $chatRepository;

    /**
     * ResumeController constructor.
     *
     * @param ChatRepository $chatRepository
     */
    public function __construct(ChatRepository $chatRepository)
    {
        $this->chatRepository = $chatRepository;
    }

    /**
     * @Route(
     *     "/{_locale<en|ru>}/resume/list/",
     *     name="resume_list",
     *     methods={"GET"},
     * )
     * @IsGranted("ROLE_APPLICANT")
     *
     * @return Response
     */
    public function list(): Response
    {
        $this->denyAccessUnlessGranted(
            'ROLE_APPLICANT',
            null,
            'User tried to access a page'
        );

        return $this->render('resume/list.html.twig');
    }

    /**
     * @Route("/{_locale<en|ru>}/resume/create", name="resume_create", methods={"GET", "POST"})
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

        /** @var User $user */
        $user = $this->getUser();
        $resume = new Resume();

        $form = $this->createForm(ResumeType::class, $resume);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $resume->setUser($user);

            $em->persist($resume);
            $em->flush();

            return $this->redirectToRoute(
                'resume_list'
            );
        }

        return $this->render('resume/create.html.twig', [
            'form' => $form->createView(),
            'appFacebookId' => $this->getParameter('app_facebook_id')
        ]);
    }

    /**
     * @Route("/{_locale<en|ru>}/resume/{id}", name="resume_show", methods="GET", requirements={"id" = "\d+"})
     *
     * @Entity("resume", expr="repository.find(id)")
     *
     * @param Resume $resume
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function show(Request $request, Resume $resume, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($request->query->get('id')) {
            /** @var JobApplication $jobApplication */
            $jobApplication = $this->getDoctrine()
                ->getRepository(JobApplication::class)
                ->find($request->query->get('id'));

            if ($jobApplication && ($jobApplication->getJob()->getCompany()->getUser() === $user)) {
                $jobApplication->setViewed(true);
                $em->persist($jobApplication);
                $em->flush();
            }
        }
        if ($user->hasRole('ROLE_EMPLOYER')) {
            /** @var Chat $hasRoom */
            $hasRoom = $this->chatRepository->checkRoomByUsers($resume->getUser(), $user);
        }

        return $this->render('resume/show.html.twig', [
            'resume' => $resume,
            'room'   => $hasRoom ? $hasRoom->getRoom() : Uuid::uuid4(),
        ]);
    }

    /**
     * @Route("/resume/edit/delete/{id}", name="resume_delete", methods="POST")
     * @Entity("resume", expr="repository.find(id)")
     *
     * @param Resume                 $resume
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function delete(Resume $resume, EntityManagerInterface $em): Response
    {
        if ($this->getUser() !== $resume->getUser()) {
            throw new Exception('You not have permissions');
        }
        $em->remove($resume);
        $em->flush();

        return $this->successMessage('Delete success');
    }

    /**
     * @Route("/{_locale<en|ru>}/resume/{id}/edit", name="resume_edit", methods={"GET", "POST"})
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
     * @Route("/{_locale<en|ru>}/job/{id}/resume/choise", name="resume_choice")
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
     * @Entity("resume", expr="repository.find(resume_id)")
     * @param EntityManagerInterface $em
     * @param Job $job
     * @param Resume $resume
     * @param ProducerInterface $producer
     * @IsGranted("ROLE_APPLICANT")
     *
     * @return Response
     */
    public function confirm(ProducerInterface $producer, EntityManagerInterface $em, Job $job, Resume $resume): Response
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

        $producer->publish(json_encode([
            'resume'       => $resume->getId(),
            'job'          => $job->getId(),
            'address'      => 'alkatras4321@gmail.com',
            'name'         => 'Jobeet',
            'to'           => $jobApplication->getJob()->getEmail(),
            'htmlTemplate' => 'resume/resume_email.html.twig',
            'subject'      => 'Job response'
        ]));

        return $this->redirectToRoute('job_list');
    }
}
