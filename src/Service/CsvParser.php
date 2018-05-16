<?php


namespace App\Service;


use App\Controller\Admin\ParticipantController;
use App\Entity\Event;
use App\Entity\Media;
use App\Entity\Participant;
use App\Entity\ProjectType;
use App\Entity\Team;
use App\Helper\LoggerTrait;
use App\Serializer\PersonNameConverter;
use Doctrine\Common\Persistence\ManagerRegistry;
use Endroid\QrCode\QrCode;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use App\Entity\Person;

class CsvParser
{
    use LoggerTrait;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $em;

    /**
     * @var string
     */
    private $token;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var string
     */
    private $rootDir;

    /**
     * @var S3Service
     */
    private $s3Service;
    /**
     * @var string
     */
    private $folderQr;

    /**
     * CsvParser constructor.
     */
    public function __construct(
        ManagerRegistry $doctrine,
        UrlGeneratorInterface $urlGenerator,
        S3Service $s3Service,
        string $token,
        string $rootDir,
        string $folderQr)
    {
        $this->em = $doctrine->getManager();
        $this->token = $token;
        $this->urlGenerator = $urlGenerator;
        $this->rootDir = $rootDir;
        $this->s3Service = $s3Service;
        $this->folderQr = $folderQr;
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
            $existingParticipant = $this->em->getRepository(Participant::class)
                ->findByPersonAndEvent($person, $event);

            $participant = $existingParticipant ? $existingParticipant : new Participant();
            $participant->setEvent($event);
            $participant->setPerson($person);

            $projectType = $this->em->getRepository(ProjectType::class)
                ->findByStr($row['Який проект ти хочеш робити']);

            if ($projectType) {
                $participant->setProjectType($projectType);
            }

            $participant->setActivationCode($this->generateCode($person->getEmail() . $participant->getEvent()->getTitle()));

            if ($url = $this->generateQrCode($participant)) {
                $media = new Media();
                $media->setUrl($url);

                $this->em->persist($media);
                $this->em->flush();

                $participant->setActivationQr($media);
            }

            //process team
            if ($row["Чи є в тебе команда? "] === "Так") {
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
            }

            $this->em->persist($participant);
            $this->em->flush();

            $success++;
        }

        return [
            'success_count' => $success
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

    private function generateUrl(Participant $participant)
    {
        return $this->urlGenerator->generate(
            'participant_activate',
            [ParticipantController::ACTIVATION_PARAM => $participant->getActivationCode()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    /**
     * @param Participant $participant
     * @return mixed
     * @throws \Exception
     */
    private function generateQrCode(Participant $participant)
    {
        $filesDir = "{$this->rootDir}/../var/files";
        $personId = $participant->getPerson()->getId();

        $fileName = "qrcode_{$personId}";
        $fileName = Media::getPrefixName($fileName) . ".png";

        $pathToFile = "$filesDir/$fileName";
        $qrCode = new QrCode($this->generateUrl($participant));

        if (!is_dir($filesDir)) {
            mkdir($filesDir);
        }

        $qrCode->writeFile($pathToFile);

        try {
            $result = $this
                ->s3Service
                ->putFile("{$this->folderQr}/$fileName", $pathToFile, S3Service::ACL_PUBLIC_READ);

            @unlink($pathToFile);

            return $result['ObjectURL'];
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }
}