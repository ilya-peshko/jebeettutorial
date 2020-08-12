<?php

namespace App\Controller\API;

use App\Entity\Resume;
use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ResumeController
 * @package App\Controller\API
 */
class ResumeController extends AbstractController
{
    /**
     * Lists all job entities.
     *
     * @Route("/api/user/{id}/resume/list/", name="api_resume", methods={"GET"})
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param EntityManagerInterface $em
     * @param User $user
     * @ParamConverter("user", class="App:User\User")
     *
     * @return Response
     */
    public function list(PaginatorInterface $paginator, User $user, EntityManagerInterface $em, Request $request): Response
    {
        $resumes = $paginator->paginate(
            $this->getDoctrine()
                ->getRepository(Resume::class)
                ->getAllResumesByUser($user, $request->get('query')),
            $request->get('page'),
            $this->getParameter('max_items_on_page')
        );

        return $this->render('api/api_resume_list.html.twig', [
            'resumes'  => $resumes,
        ]);
    }
}