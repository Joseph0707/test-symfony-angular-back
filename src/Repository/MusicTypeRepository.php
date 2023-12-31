<?php

namespace App\Repository;

use App\Entity\MusicType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MusicType>
 *
 * @method MusicType|null find($id, $lockMode = null, $lockVersion = null)
 * @method MusicType|null findOneBy(array $criteria, array $orderBy = null)
 * @method MusicType[]    findAll()
 * @method MusicType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MusicTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MusicType::class);
    }

    /**
     * @return MusicType[] Returns an array of MusicType objects
     */
    public function getAllMusicTypes(): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByName($type): ?MusicType
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.type = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
