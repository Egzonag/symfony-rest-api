<?php

namespace App\Controller;

use App\Repository\LikesRepository;
use App\Repository\PostsRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikesController extends AbstractController
{
    private $likesRespository;
    private $postsRespository;
    private $userRepository;

    public function __construct(PostsRepository $postsRespository, UserRepository $userRepository, LikesRepository $likesRespository)
    {
        $this->userRepository = $userRepository;
        $this->postsRespository = $postsRespository;
        $this->likesRespository = $likesRespository;
    }
    /**
     * @Route("/{id}/{state}/{post}", name="likes", methods={"POST"})
     */
    public function like($id, $post, $state)
    {
        $likes = $this->likesRespository->findOneBy([
            'post' => $post,
            'user' => $id
        ]);
        $user = $this->userRepository->findOneBy(['id' => $id]);
        $posts = $this->postsRespository->findOneBy(['id' => $post]);

        if ($likes == null) {
            $this->likesRespository->newlike($user, $posts, $state);
            return new JsonResponse("You " . $state . " this post!", Response::HTTP_OK);
        } else {
            if ($state == "like") {
                $likes->setLikes(1);
                $likes->setUnlikes(0);
            } else {
                $likes->setLikes(0);
                $likes->setUnlikes(1);
            }
            $this->likesRespository->updateLikes($likes);
            return new JsonResponse("You just changed your vote to " . $state, Response::HTTP_OK);
        }
    }

    /**
     * @Route("/posts/{post_id}/likes", name="all_likes", methods={"GET"})
     */
    public function viewLikes($post_id)
    {
        $allLikes = $this->likesRespository->getAllLikes($post_id);
        return new JsonResponse($allLikes, Response::HTTP_OK);
    }
}
