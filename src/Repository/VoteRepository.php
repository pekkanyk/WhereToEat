<?php

namespace App\Repository;

use App\Entity\Vote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vote>
 *
 * @method Vote|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vote|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vote[]    findAll()
 * @method Vote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vote::class);
    }

    public function add(Vote $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Vote $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    public function hasUserVotedDate($user,$date): ?Vote
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.user_id = :user')
            ->andWhere('g.date = :date')
            ->setParameter('user', $user)
            ->setParameter('date', $date)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
  
    public function voteCount($wte)
    {
        return $this->createQueryBuilder('v')
            ->select('COUNT (v) AS lkm')
            ->andWhere('v.whereToEat = :wte')
            ->setParameter('wte', $wte)
            ->orderBy('lkm', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function topVotes($wte)
    {
        return $this->createQueryBuilder('v')
            ->select('COUNT (v) AS lkm, (v.restaurant_id) AS rr')
            ->andWhere('v.whereToEat = :wte')
            ->groupBy('rr')
            ->setParameter('wte', $wte)
            ->orderBy('lkm','DESC')
            ->getQuery()
            ->getResult()
            ;
    }


//    /**
//     * @return Vote[] Returns an array of Vote objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Vote
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
