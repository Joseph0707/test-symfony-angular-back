<?php

namespace App\Repository;

use App\Entity\Founder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Founder>
 *
 * @method Founder|null find($id, $lockMode = null, $lockVersion = null)
 * @method Founder|null findOneBy(array $criteria, array $orderBy = null)
 * @method Founder[]    findAll()
 * @method Founder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FounderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Founder::class);
    }

//    /**
//     * @return Founder[] Returns an array of Founder objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Founder
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
