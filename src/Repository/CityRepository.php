<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<City>
 *
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

   /**
    * @return City[] Returns an array of City objects
    */
   public function getAllCities(): array
   {
       return $this->createQueryBuilder('c')
           ->orderBy('c.id', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

   public function findByName($name): ?City
   {
       return $this->createQueryBuilder('c')
           ->andWhere('c.name = :name')
           ->setParameter('name', $name)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }
}
