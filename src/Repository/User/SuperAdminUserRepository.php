<?php

namespace App\Repository\User;

use App\Entity\User\SuperAdminUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SuperAdminUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method SuperAdminUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method SuperAdminUser[]    findAll()
 * @method SuperAdminUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SuperAdminUserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SuperAdminUser::class);
    }

//    /**
//     * @return SuperAdminUser[] Returns an array of SuperAdminUser objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SuperAdminUser
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
