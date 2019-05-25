<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Participant;
use App\Entity\Person;
use App\Form\Model\PersonParticipantModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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

    public function searchByLastName(string $str)
    {
        return $this->createQueryBuilder('participant')
            ->leftJoin('participant.person', 'person')
            ->addOrderBy('person.lastName', 'ASC')
            ->where('person.lastName LIKE :str')
            ->setParameter('str', "%$str%")
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

    public function filterByForm(PersonParticipantModel $model)
    {
        $qb = $this->createQueryBuilder('participant')
            ->leftJoin('participant.person', 'person')
            ->addOrderBy('person.lastName', 'ASC');

        if ($model->isEmployment() !== null) {
            $qb->andWhere('person.employment = :isEmployment')
                ->setParameter('isEmployment', $model->isEmployment());
        }

        if ($model->isNotified() !== null) {
            $qb->andWhere('participant.isNotified = :isNotified')
                ->setParameter('isNotified', $model->isNotified());
        }

        if ($model->isInternship() !== null) {
            $qb->andWhere('person.internship = :isInternship')
                ->setParameter('isInternship', $model->isInternship());
        }

        if ($model->isActive() !== null) {
            $qb->andWhere('participant.isActive = :isActive')
                ->setParameter('isActive', $model->isActive());
        }

        if ($model->getEvent() !== null) {
            $qb->andWhere('participant.event = :event')
                ->setParameter('event', $model->getEvent());
        }

        if ($model->getProjectType() !== null) {
            $qb->andWhere('participant.projectType = :projectType')
                ->setParameter('projectType', $model->getProjectType());
        }

        if ($model->isNoTeam() !== null) {
            $qb->andWhere('participant.team is NULL');
        } else if ($model->getTeam() !== null) {
            $qb->andWhere('participant.team = :team')
                ->setParameter('team', $model->getTeam());
        }

        if ($model->hasPhoto() !== null) {
            if ($model->hasPhoto()) {
                //TODO: add supporting if media.url is null
//                SELECT participant.id FROM person
//    LEFT join media
//    ON person.photo_id = media.id
//    LEFT JOIN participant
//    ON person.id = participant.person_id
//    WHERE participant.event_id = 2 AND participant.is_active = true AND media.url IS NULL
//                ;
                $qb->andWhere('person.photo IS NOT NULL');
            } else {
                $qb->andWhere('person.photo IS NULL');
            }
        }

        $qb->getQuery();

        return $qb;
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
