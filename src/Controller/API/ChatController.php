<?php

namespace App\Controller\API;

use App\Entity\Chat;
use App\Entity\Company;
use App\Entity\User\User;
use App\Event\ChatMessagesEvent;
use App\Repository\ChatRepository;
use App\Repository\CompanyRepository;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ChatController
 *
 * @package App\Controller\API
 */
class ChatController extends BaseController
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ChatRepository
     */
    private $chatRepository;

    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * ChatController constructor.
     *
     * @param UserRepository           $userRepository
     * @param ChatRepository           $chatRepository
     * @param CompanyRepository        $companyRepository
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        UserRepository $userRepository,
        ChatRepository $chatRepository,
        CompanyRepository $companyRepository,
        EventDispatcherInterface $dispatcher
    ) {
        $this->userRepository    = $userRepository;
        $this->chatRepository    = $chatRepository;
        $this->companyRepository = $companyRepository;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @Route("/api/save-message", name="api_save_message", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function save(Request $request): Response
    {
        $body = json_decode($request->getContent(), true);

        /** @var User $user */
        $user     = $this->userRepository->find($body['userId']);
        $hasRoom  = $this->chatRepository->checkNewRoom($body['room']);

        if (empty($hasRoom)) {
            $this->createRoom($body, $user);

            return new JsonResponse();
        }
        $this->saveChat($body, $user);

        $event = new ChatMessagesEvent($user, $body['room']);
        $this->dispatcher->dispatch($event);

        // TODO Add response
        return new JsonResponse();
    }

    /**
     * @param array $body
     * @param User  $user
     */
    private function createRoom(array $body, User $user): void
    {
        $this->saveChat($body, $user);

        /** @var Company $company */
        $company = $this->companyRepository->find($body['company']);

        if ($user !== $company->getUser()) {
            $body['message'] = '';
            $this->saveChat($body, $company->getUser());
        }
    }

    /**
     * @param array $body
     * @param User  $user
     */
    private function saveChat(array $body, User $user): void
    {
        $chat = new Chat();
        $chat->setUser($user)
            ->setMessage($body['message'])
            ->setRoom($body['room'])
            ->setSendDate(new \DateTime())
            ->setViewed(0);
        $user->addChat($chat);

        $this->getDoctrine()->getManager()->persist($chat);
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();
    }
}
