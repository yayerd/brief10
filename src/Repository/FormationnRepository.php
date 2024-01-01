<?php

namespace App\Repository;

use App\Entity\Formationn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Formationn>
 *
 * @method Formationn|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formationn|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formationn[]    findAll()
 * @method Formationn[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationnRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formationn::class);
    }

//    /**
//     * @return Formationn[] Returns an array of Formationn objects
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

//    public function findOneBySomeField($value): ?Formationn
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
