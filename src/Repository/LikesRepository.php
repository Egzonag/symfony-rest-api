<?php

namespace App\Repository;

use App\Entity\Likes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method Likes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Likes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Likes[]    findAll()
 * @method Likes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikesRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $manager

    ) {
        parent::__construct($registry, Likes::class);
        $this->manager = $manager;
    }

    public function newlike($user, $post, $state)
    {
        $newlike = new Likes();

        $newlike->setUserId($user);
        $newlike->setPostId($post);
        if ($state == "like") {
            $newlike->setLikes(1);
            $newlike->setUnlikes(0);
        } else {
            $newlike->setLikes(0);
            $newlike->setUnlikes(1);
        }

        $this->manager->persist($newlike);
        $this->manager->flush();
    }
    //update likes
    public function updateLikes(Likes $likes): Likes
    {
        $this->manager->persist($likes);
        $this->manager->flush();

        return $likes;
    }

    public function getAllLikes($post_id)
    {
        $qb = $this->createQueryBuilder('l')
            ->select('identity(l.post) as POST', 'SUM(l.likes) AS TOTAL_LIKES')
            ->groupBy('l.post')
            ->where('l.post = :post_id')
            ->setParameter('post_id', $post_id);

        return $qb->getQuery()
            ->getResult();
    }
}
