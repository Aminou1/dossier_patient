<?php

namespace App\Repository;

use App\Entity\TypePrescription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypePrescription|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypePrescription|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypePrescription[]    findAll()
 * @method TypePrescription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypePrescriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypePrescription::class);
    }

    // /**
    //  * @return TypePrescription[] Returns an array of TypePrescription objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypePrescription
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
