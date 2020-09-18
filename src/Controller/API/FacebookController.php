<?php

namespace App\Controller\API;

use App\Service\FacebookMethodsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FacebookController extends AbstractController
{
    /**
     * @Route("/api/get-facebook-user", name="api_get_facebook", methods={"POST"})
     *
     * @param Request $request
     * @param FacebookMethodsService $facebookMethods
     *
     * @return Response
     */
    public function facebookUserInfo(Request $request, FacebookMethodsService $facebookMethods): Response
    {
        $body = json_decode($request->getContent(), true);
        $options = [
            'app_id' => $this->getParameter('app_facebook_id'),
            'app_secret' => $this->getParameter('app_facebook_secret'),
        ];

        return $facebookMethods->getFacebookUserInfo($body, $options);
    }
}
