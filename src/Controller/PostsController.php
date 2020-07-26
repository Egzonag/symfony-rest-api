<?php

namespace App\Controller;

use App\Repository\PostsRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
{
    private $postsRespository;
    private $userRepository;

    public function __construct(PostsRepository $postsRespository, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->postsRespository = $postsRespository;
    }

    /**
     * @Route("/posts", name="posts")
     */
    public function getPosts(PostsRepository $posts): JsonResponse
    {
        $data = $posts->findAll();
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}/posts/create", name="new_post", methods={"POST"})
     */
    public function create(Request $request, $id): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        $this->postsRespository->newPost($data, $user);

        return new JsonResponse(['status' => 'New post has been created!'], Response::HTTP_CREATED);
    }
}
