<?php

namespace App\Repository\Event;

use App\Entity\Event\MeetupEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MeetupEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method MeetupEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method MeetupEvent[]    findAll()
 * @method MeetupEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeetupEventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MeetupEvent::class);
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
