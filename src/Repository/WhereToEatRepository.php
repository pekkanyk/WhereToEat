<?php

namespace App\Repository;

use App\Entity\WhereToEat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WhereToEat>
 *
 * @method WhereToEat|null find($id, $lockMode = null, $lockVersion = null)
 * @method WhereToEat|null findOneBy(array $criteria, array $orderBy = null)
 * @method WhereToEat[]    findAll()
 * @method WhereToEat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WhereToEatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WhereToEat::class);
    }

    public function add(WhereToEat $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WhereToEat $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findForGrpDate($id,$date): ?WhereToEat
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.grp = :grpId')
            ->andWhere('g.date = :date')
            ->setParameter('grpId', $id)
            ->setParameter('date', $date->format('Y-m-d'))
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /*
    public function hasUserVotedDate($user,$date){
        return $this->createQueryBuilder('g')
            ->andWhere(':user NOT MEMBER OF r.')
            ->andWhere('g.date = :date')
            ->setParameter('user', $user)
            ->setParameter('date', $date)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
*/
  


//    /**
//     * @return WhereToEat[] Returns an array of WhereToEat objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?WhereToEat
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
