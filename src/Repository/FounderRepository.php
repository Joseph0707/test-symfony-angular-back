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
}
