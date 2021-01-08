<?php

namespace App\Controller;

use App\Controller\API\BaseController;
use App\Entity\Chat;
use App\Entity\Company;
use App\Entity\User\User;
use App\Form\CompanyType;
use App\Repository\ChatRepository;
use App\Repository\CompanyRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends BaseController
{
    /**
     * @var ChatRepository
     */
    private $chatRepository;

    /**
     * @var CompanyRepository
     */
    private $companyRepository;
    private $userRepository;

    /**
     * CompanyController constructor.
     * @param CompanyRepository $companyRepository
     * @param ChatRepository $chatRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        CompanyRepository $companyRepository,
        ChatRepository $chatRepository,
        UserRepository $userRepository
    ) {
        $this->companyRepository = $companyRepository;
        $this->chatRepository    = $chatRepository;
        $this->userRepository    = $userRepository;
    }

    /**
     * @Route("/{_locale<en|ru>}/company/create", name="company_create", methods={"GET", "POST"})
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

        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $company->setUser($this->getUser());
            $em->persist($company);
            $em->flush();

            return $this->redirectToRoute(
                'company_show',
                ['id' => $company->getId()]
            );
        }

        return $this->render('company/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{_locale<en|ru>}/company/show/{id}", name="company_show", methods="GET", requirements={"id" = "\d+"})
     * @Entity("company", expr="repository.find(id)")
     *
     * @param Company $company
     *
     * @return Response
     */
    public function show(Company $company): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user !== $company->getUser()) {

            /** @var Chat $hasRoom */
            $hasRoom = $this->chatRepository->checkRoomByUsers($user, $company->getUser());

            if ($hasRoom) {
                return $this->render('company/show.html.twig', [
                    'company' => $company,
                    'room'    => $hasRoom->getRoom()
                ]);
            }

            return $this->render('company/show.html.twig', [
                'company' => $company,
                'room'    => Uuid::uuid4()
            ]);
        }

        return $this->render('company/show.html.twig', [
            'company' => $company,
        ]);
    }

    /**
     * @Route("/{_locale<en|ru>}/company/edit", name="company_edit", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @IsGranted("ROLE_EMPLOYER")
     *
     * @return Response
     */
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted(
            'ROLE_EMPLOYER',
            null,
            'User tried to access a page'
        );
        /** @var User $user */
        $user = $this->getUser();

        /** @var Company $company */
        $company =  $this->getDoctrine()->getRepository(Company::class)->getCompanyByUser($user);

        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('company_show', [
                'id' => $user->getCompany()->getId()
            ]);
        }
        return $this->render('company/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("company/edit/delete/{id}", name="company_delete", methods="POST")
     * @Entity("company", expr="repository.find(id)")
     * @param Company                $company
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function delete(Company $company, EntityManagerInterface $em): Response
    {
        if ($this->getUser() !== $company->getUser()) {
            throw new Exception('You not have permissions');
        }
        $em->remove($company);
        $em->flush();

        return $this->successMessage('Delete success');
    }

    /**
     * @Route("/{_locale<en|ru>}/company/{id}/chat/{room}", name="company_chat", methods={"GET", "POST"})
     * @Entity("company", expr="repository.find(id)")
     *
     * @param Company                $company
     * @param Request                $request
     * @param EntityManagerInterface $em
     * @param string                 $room     Room.
     *
     * @return Response
     */
    public function chating(Company $company, Request $request, string $room, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        /** @var Chat $messages */
        $messages = $this->chatRepository->getMessagesByRoom($room);

        if ($messages) {
            $companionMessages = $this->chatRepository->getCompanionMessagesInRoom($room, $user);
            $userMessages      = $this->chatRepository->getUserMessagesInRoom($room, $user);

            return $this->render('company/chat.html.twig', [
                'company'           => $company,
                'messages'          => $messages,
                'room'              => $room,
            ]);
        }

        return $this->render('company/chat.html.twig', [
            'company'           => $company,
            'room'              => $room,
            'hasMessages'       => false,
        ]);
    }

    /**
     * @Route("/{_locale<en|ru>}/company/{id}/chats/", name="company_chats", methods={"GET"})
     * @Entity("company", expr="repository.find(id)")
     *
     * @param Company $company
     * @param Request $request
     *
     * @return Response
     */
    public function chats(Company $company, Request $request): Response
    {
        $chats = $this->chatRepository->getUniqueChatsByEmployer($company->getUser());

        return $this->render('company/chats.html.twig', [
            'company' => $company,
            'chats'   => $chats
        ]);
    }
}