<?php

namespace App\Repository\Participant;

use App\Entity\Participant\MeetupParticipant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MeetupParticipant|null find($id, $lockMode = null, $lockVersion = null)
 * @method MeetupParticipant|null findOneBy(array $criteria, array $orderBy = null)
 * @method MeetupParticipant[]    findAll()
 * @method MeetupParticipant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HackathonParticipantRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MeetupParticipant::class);
    }

    // /**
    //  * @return MeetupParticipant[] Returns an array of MeetupParticipant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MeetupParticipant
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
