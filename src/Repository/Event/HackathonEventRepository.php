<?php

namespace App\Repository\Event;

use App\Entity\HackathonEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method HackathonEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method HackathonEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method HackathonEvent[]    findAll()
 * @method HackathonEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HackathonEventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, HackathonEvent::class);
    }

    public function getAll()
    {
        return $this->createQueryBuilder('e')->getQuery();
    }

//    /**
//     * @return Event[] Returns an array of Event objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
