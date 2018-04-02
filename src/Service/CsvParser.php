<?php


namespace App\Service;


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
     * @return array
     */
    public function parse($fileData) {
        $encoders = [new CsvEncoder()];
        $nameConverter = new PersonNameConverter();
        $normalizers = [new ObjectNormalizer(null, $nameConverter)];
        $serializer = new Serializer($normalizers, $encoders);

        $rows = $serializer->decode(file_get_contents($fileData), 'csv');

        $success = 0;
        foreach ($rows as $row) {
            if ($person = $serializer->denormalize($row, Person::class)) {
                $this->em->persist($person);
                $this->em->flush();

                $success++;
            }
        }

        return [
            'success' => $success,
            'error' => 'message'
        ];
    }
}