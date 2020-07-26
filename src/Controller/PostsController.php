<?php

namespace App\Controller;

use App\Entity\Posts;
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
    public function getPosts(PostsRepository $postsRepository): JsonResponse
    {
        $posts = $postsRepository->findBy(array('status' => 'unArchived'));

        $data = array('post' => array());
        foreach ($posts as $post) {
            $data['posts'][] = $this->serializePost($post);
        }
        //$response = new Response(json_encode($data), 200);


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

    private function serializePost(Posts $posts)
    {
        return array(
            'title' => $posts->getTitle(),
            'desc' => $posts->getDescription(),
            'status' => $posts->getStatus(),
            'created' => $posts->getCreated(),
        );
    }

    /**
     * @Route("/{id}/posts/{post}/update", name="update_post", methods={"PUT"})
     */
    public function update(Request $request, $post): JsonResponse
    {
        $post = $this->postsRespository->findOneBy(['id' => $post]);
        $data = json_decode($request->getContent(), true);


        empty($data['title']) ? true : $post->setTitle($data['title']);
        empty($data['desc']) ? true : $post->setDescription($data['desc']);


        $this->postsRespository->updatePost($post);

        return new JsonResponse("Post updated!", Response::HTTP_OK);
    }

    /**
     * @Route("/{id}/posts/{post}/{status}", name="update_post", methods={"PUT"})
     */
    public function changeStatus(Request $request, $post, $status, $id): JsonResponse
    {
        $post = $this->postsRespository->findOneBy(['id' => $post, 'user' => $id]);
        //$data = json_decode($request->getContent(), true);

        if (empty($post)) {
            return new JsonResponse("You can only change posts created by you. ");
        }
        $post->setStatus($status);

        $this->postsRespository->updatePost($post);

        return new JsonResponse("Status changed to: " . $status, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}/posts/{post}/delete", name="delete_customer", methods={"DELETE"})
     */
    public function delete($post, $id): JsonResponse
    {
        $post = $this->postsRespository->findOneBy(['id' => $post, 'user' => $id]);

        $this->postsRespository->removePost($post);

        return new JsonResponse("Post deleted!", Response::HTTP_OK);
    }
}
