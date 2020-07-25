<?php

namespace App\Controller;

use App\Repository\PostsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
{
    /**
     * @Route("/posts", name="posts")
     */
    public function getPosts(PostsRepository $posts): JsonResponse
    {
        $data = $posts->findAll();
        return new JsonResponse($data, Response::HTTP_OK);
    }
}
