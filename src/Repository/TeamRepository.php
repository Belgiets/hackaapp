<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findAll()
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Team::class);
    }

    /**
     * @param string $name
     * @param Event $event
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByNameAndEvent($name, $event)
    {
        return $this->createQueryBuilder('t')
            ->where('t.event = :event_id')->setParameter('event_id', $event->getId())
            ->andWhere('t.name = :name')->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function getAll()
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.event', 'e')
            ->getQuery();
    }
//    /**
//     * @return Team[] Returns an array of Team objects
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
    public function findOneBySomeField($value): ?Team
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
