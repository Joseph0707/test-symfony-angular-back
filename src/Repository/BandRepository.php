<?php

namespace App\Repository;

use App\Entity\Band;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Band>
 *
 * @method Band|null find($id, $lockMode = null, $lockVersion = null)
 * @method Band|null findOneBy(array $criteria, array $orderBy = null)
 * @method Band[]    findAll()
 * @method Band[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Band::class);
    }

   /**
    * @return Band[] Returns an array of Band objects
    */
   public function findAllByName($name): array
   {
       return $this->createQueryBuilder('b')
           ->andWhere('b.groupName LIKE :name')
           ->setParameter('name', '%'.$name.'%')
           ->orderBy('b.id', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }
}
