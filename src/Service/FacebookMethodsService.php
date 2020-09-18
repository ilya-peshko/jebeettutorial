<?php

namespace App\Service;

use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class FacebookMethodsService
 * @package App\Service
 */
class FacebookMethodsService
{
    /**
     * @param $body
     * @param $options
     *
     * @return JsonResponse
     */
    public function getFacebookUserInfo($body, $options): JsonResponse
    {
        try {
            $facebook = new Facebook($options);
            $response = $facebook->get(
                '/'.$body['userId'].'?fields=first_name,last_name,birthday,hometown,gender',
                $body['accessToken']
            );
            return new JsonResponse($response->getDecodedBody());
        } catch (FacebookSDKException $e) {
            return new JsonResponse($e->getMessage());
        }
    }
}