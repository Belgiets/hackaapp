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
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $em;

    /**
     * @var string
     */
    private $token;

    /**
     * CsvParser constructor.
     */
    public function __construct(ManagerRegistry $doctrine, string $token)
    {
        $this->em = $doctrine->getManager();
        $this->token = $token;
    }

    /**
     * @param $fileData
     * @param Event $event
     * @return array
     * @throws \Exception
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

            $this->em->persist($person);
            $this->em->flush();

            //process participant
            $participant = new Participant($event);
            $participant->setPerson($person);

            $participant->setActivationCode($this->generateCode($person->getEmail()));

            //process team
            if ($teamName = empty($row["Назва команди "]) ? false : $row["Назва команди "]) {
                $team = $this->em->getRepository(Team::class)->findByNameAndEvent($teamName, $event);

                if (!$team) {
                    $team = new Team();
                    $team->setName($teamName);
                    $team->setEvent($event);
                }

                $this->em->persist($team);
                $this->em->flush();

                $participant->setTeam($team);
            }

            $this->em->persist($participant);
            $this->em->flush();

            $success++;
        }

        return [
            'success' => $success,
            'error' => 'message'
        ];
    }

    /**
     * generate activation code
     *
     * @return string
     * @throws \Exception
     */
    private function generateCode($str)
    {
        return md5($this->token . md5($str));
    }
}