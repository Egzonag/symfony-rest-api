<?php

namespace App\Repository;

use App\Entity\Comments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comments[]    findAll()
 * @method Comments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentsRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $manager

    ) {
        parent::__construct($registry, Comments::class);
        $this->manager = $manager;
    }

    //creating a comment
    public function newComment($data, $user, $post)
    {
        $comment = new Comments();
        $comment
            ->setPostId($post)
            ->setUserId($user)
            ->setComment($data['comment']);

        $this->manager->persist($comment);
        $this->manager->flush();
    }

    //Delete comment
    public function removeComment(Comments $comments): Comments
    {
        $this->manager->remove($comments);
        $this->manager->flush();

        return $comments;
    }
 
}
