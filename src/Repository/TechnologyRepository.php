<?php

namespace App\Repository;

use App\Entity\Technology;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Technology|null find($id, $lockMode = null, $lockVersion = null)
 * @method Technology|null findOneBy(array $criteria, array $orderBy = null)
 * @method Technology[]    findAll()
 * @method Technology[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TechnologyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Technology::class);
    }

    public function getAll()
    {
        return $this->createQueryBuilder('t')->getQuery();
    }

//    /**
//     * @param string $str
//     * @return mixed
//     * @throws \Doctrine\ORM\NonUniqueResultException
//     */
//    public function findByTitle(string $str)
//    {
//        return $this->createQueryBuilder('t')
//            ->where('t.title LIKE :str')
//            ->setParameter('str', "%$str%")
//            ->getQuery()
//            ->getOneOrNullResult();
//    }
//    /**
//     * @return Technology[] Returns an array of Technology objects
//     */
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
    public function findOneBySomeField($value): ?Technology
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
