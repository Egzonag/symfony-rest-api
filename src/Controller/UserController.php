<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\ProfileRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends AbstractController
{
    private $userRepository;
    private $profileRepository;

    public function __construct(UserRepository $userRepository, ProfileRepository $profileRepository)
    {
        $this->userRepository = $userRepository;
        $this->profileRepository = $profileRepository;
    }

    /**
     * @Route("/users/{id}", name="user_profile", methods={"GET"})
     */
    public function profile($id): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);
        $profile = $this->profileRepository->findOneBy(['user' => $id]);

        $data = [
            'id' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'dob' => $profile->getDob(),
            'gender' => $profile->getGender(),
            'country' => $profile->getCountry(),
            'city' => $profile->getCity(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/users/register", name="register", methods={"POST"})
     */
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $newuser = new User();

        $newuser
            ->setFirstName($data['firstName'])
            ->setLastName($data['lastName'])
            ->setEmail($data['email'])
            ->setPassword($data['password']);

        //create profile    
        $this->profileRepository->saveProfile($data, $newuser);

        return new JsonResponse(['status' => 'User registered!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/users/{id}/update", name="update_user", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);
        $profile = $this->profileRepository->findOneBy(['user' => $id]);
        $data = json_decode($request->getContent(), true);

        // dd($data);
        empty($data['firstName']) ? true : $user->setFirstName($data['firstName']);
        empty($data['lastName']) ? true : $user->setLastName($data['lastName']);
        empty($data['email']) ? true : $user->setEmail($data['email']);
        empty($data['password']) ? true : $user->setPassword($data['password']);
        empty($data['dob']) ? true : $profile->setDob(\DateTime::createFromFormat('Y-m-d', $data['dob']));
        empty($data['gender']) ? true : $profile->setGender($data['gender']);
        empty($data['country']) ? true : $profile->setCountry($data['country']);
        empty($data['city']) ? true : $profile->setCity($data['city']);


        $this->userRepository->updateUser($user, $profile);

        return new JsonResponse("Profile updated!", Response::HTTP_OK);
    }
}
