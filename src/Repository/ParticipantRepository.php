<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Participant;
use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Participant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participant[]    findAll()
 * @method Participant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Participant::class);
    }

    public function getAll()
    {
        return $this->createQueryBuilder('participant')
            ->leftJoin('participant.person', 'person')
            ->addOrderBy('person.lastName', 'ASC')
            ->getQuery();
    }

    /**
     * @param Person $person
     * @param Event $event
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByPersonAndEvent($person, $event)
    {
        return $this->createQueryBuilder('p')
            ->where('p.person = :person')
            ->setParameter('person', $person->getId())
            ->andWhere('p.event = :event')
            ->setParameter('event', $event->getId())
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
//    /**
//     * @return Participant[] Returns an array of Participant objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Participant
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
