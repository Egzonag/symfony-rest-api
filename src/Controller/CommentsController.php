<?php

namespace App\Controller;

use App\Repository\CommentsRepository;
use App\Repository\PostsRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CommentsController extends AbstractController
{

    private $postsRespository;
    private $commentsRepository;
    private $userRepository;

    public function __construct(PostsRepository $postsRespository, CommentsRepository $commentsRepository, UserRepository $userRepository)
    {
        $this->commentsRepository = $commentsRepository;
        $this->postsRespository = $postsRespository;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/{user_id}/comment/{post_id}", name="comment", methods={"POST"})
     */
    public function comment($user_id, $post_id, Request $request): JsonResponse
    {
        $post = $this->postsRespository->findOneBy(['id' => $post_id]);
        $user = $this->userRepository->findOneBy(['id' => $user_id]);

        $data = json_decode($request->getContent(), true);

        $this->commentsRepository->newComment($data, $user, $post);
        return new JsonResponse('Your comment is saved.', Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}/comment/{post}/delete", name="delete_comment", methods={"DELETE"})
     */

    //This function deletes my comments on a particular post
    public function delete($post, $id): JsonResponse
    {
        $comment = $this->commentsRepository->findOneBy(['post' => $post, 'user' => $id]);

        $this->commentsRepository->removeComment($comment);

        return new JsonResponse("Comment deleted!", Response::HTTP_OK);
    }

}
