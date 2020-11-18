<?php

namespace App\Controller\API;

use App\Entity\Chat;
use App\Entity\User\User;
use App\Repository\UserRepository;
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
     * ChatController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository    = $userRepository;
    }

    /**
     * @Route("/api/save-message", name="api_save_message", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function saveChat(Request $request): Response
    {
        $body = json_decode($request->getContent(), true);

        /** @var User $user */
        $user = $this->userRepository->find($body['userId']);
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

        return new JsonResponse();
    }
}
