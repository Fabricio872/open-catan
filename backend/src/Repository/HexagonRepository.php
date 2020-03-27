<?php

namespace App\Repository;

use App\Entity\Hexagon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Hexagon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hexagon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hexagon[]    findAll()
 * @method Hexagon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HexagonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hexagon::class);
    }

    // /**
    //  * @return Hexagon[] Returns an array of Hexagon objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Hexagon
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
