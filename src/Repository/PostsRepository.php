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
        parent::__construct($registry, POsts::class);
        $this->manager = $manager;
    }

    //creating a user
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

    //update posts
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



    // /**
    //  * @return Posts[] Returns an array of Posts objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Posts
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
