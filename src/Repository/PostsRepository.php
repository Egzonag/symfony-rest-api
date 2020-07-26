<?php

namespace App\Repository;

use App\Entity\Posts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Posts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Posts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Posts[]    findAll()
 * @method Posts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostsRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $manager

    ) {
        parent::__construct($registry, Posts::class);
        $this->manager = $manager;
    }

    //creating a new post
    public function newPost($data, $user_id)
    {
        $newpost = new Posts();
        $newpost
            ->setTitle($data['title'])
            ->setDescription($data['desc'])
            ->setStatus('unArchived')
            ->setCreated(new \DateTime())
            ->setUserId($user_id);

        $this->manager->persist($newpost);
        $this->manager->flush();
    }

    //update existing post
    public function updatePost(Posts $posts): Posts
    {
        $this->manager->persist($posts);
        $this->manager->flush();

        return $posts;
    }

    //Delete posts
    public function removePost(Posts $posts): Posts
    {
        $this->manager->remove($posts);
        $this->manager->flush();

        return $posts;
    }

}
