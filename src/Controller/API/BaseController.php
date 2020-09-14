<?php


namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BaseController
 * @package App\Controller\API
 */
class BaseController extends AbstractController
{
    /**
     * @param string $message
     * @param int $code
     *
     * @return JsonResponse
     */
    protected function successMessage(string $message, int $code = Response::HTTP_OK): JsonResponse
    {
        return new JsonResponse([
            'code'    => $code,
            'message' => $message
        ]);
    }

    /**
     * @param string $message
     * @param int $code
     *
     * @return JsonResponse
     */
    protected function errorMessage(string $message, int $code = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return new JsonResponse([
            'code'    => $code,
            'message' => $message
        ]);
    }
}
