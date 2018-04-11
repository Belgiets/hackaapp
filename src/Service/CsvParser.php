<?php


namespace App\Service;


use App\Entity\Event;
use App\Entity\Participant;
use App\Entity\Team;
use App\Serializer\PersonNameConverter;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use App\Entity\Person;

class CsvParser
{
    private $em;

    /**
     * CsvParser constructor.
     */
    public function __construct(ManagerRegistry $manager)
    {
        $this->em = $manager->getManager();
    }

    /**
     * @param $fileData
     * @param Event $event
     * @return array
     */
    public function parse($fileData, $event) {
        $encoders = [new CsvEncoder()];
        $nameConverter = new PersonNameConverter();
        $normalizers = [new ObjectNormalizer(null, $nameConverter)];
        $serializer = new Serializer($normalizers, $encoders);

        $rows = $serializer->decode(file_get_contents($fileData), 'csv');

        $success = 0;
        foreach ($rows as $row) {
            $existingPerson = $this->em->getRepository(Person::class)
                ->findOneBy(['email' => $row['Електронна адреса (якою ти найчастіше користуєшся)']]);

            //process person
            if ($existingPerson) {
                /** @var Person $person */
                $person = $serializer->denormalize(
                    $row,
                    Person::class,
                    'array',
                    ['object_to_populate' => $existingPerson]
                );
            } else {
                /** @var Person $person */
                $person = $serializer->denormalize($row, Person::class);
            }

            //process participant
            $participant = new Participant($event);
            $person->addParticipant($participant);

            //process team
            if ($teamName = empty($row["Назва команди "]) ? false : $row["Назва команди "]) {
                // TODO: repository method to find team by name and event
                if ($team = $this->em->getRepository(Team::class)->findOneBy(['name' => $teamName])) {

                } else {
                    $team = new Team();
                }
            }

            $this->em->persist($person);
            $this->em->flush();

            $success++;
        }

        return [
            'success' => $success,
            'error' => 'message'
        ];
    }
}