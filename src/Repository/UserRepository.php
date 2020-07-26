<?php

namespace App\Repository;

use App\Entity\Profile;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{

    private $manager;
    private $profile;

    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $manager,
        ProfileRepository $profile

    ) {
        parent::__construct($registry, User::class);
        $this->manager = $manager;
    }

    //creating a user
    public function saveUser($data)
    {
        $newuser = new User();
        $newuser
            ->setFirstName($data['firstName'])
            ->setLastName($data['lastName'])
            ->setEmail($data['email'])
            ->setPassword($data['password']);

        $this->manager->persist($newuser);
        $this->profile->saveProfile($data, $newuser);
    }

    //update user profile
    public function updateUser(User $user, Profile $prof): User
    {
        $this->manager->persist($user);
        $this->manager->persist($prof);
        $this->manager->flush();

        return $user;
    }
}
