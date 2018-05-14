<?php


namespace App\Service;

use App\Controller\Admin\ParticipantController;
use App\Entity\Participant;
use App\Entity\Person;
use App\Helper\LoggerTrait;
use Aws\Ses\Exception\SesException;
use Aws\Ses\SesClient;
use Sendinblue\Mailin;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;


class Notification
{
    use LoggerTrait;

    const ENDPOINT = 'https://api.sendinblue.com/v2.0';
    const TIMEOUT = 5000;

    /**
     * @var string
     */
    private $from;

    /**
     * @var Mailin
     */
    private $mailing;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var UrlGenerator
     */
    private $generator;

    /**
     * Notification constructor.
     * @throws \Exception
     */
    public function __construct(string $from, string $apiKey, Environment $twig, UrlGeneratorInterface $generator)
    {
        $this->from = $from;
        $this->mailing = new Mailin(self::ENDPOINT, $apiKey, self::TIMEOUT);;
        $this->twig = $twig;
        $this->generator = $generator;
    }

    /**
     * @param string $subject
     * @param Participant $participant
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function notify(string $subject, Participant $participant)
    {
//        $data = [
//            'from' => [$this->from, "from email!"],
//            'to' => [$person->getEmail() => $person->getFirstName() . ' ' . $person->getLastName()],
//            'subject' => $subject,
//            'html' => $this->twig->render(
//                'emails/hackaton.html.twig',
//                ['person' => $person]
//            ),
//        ];
//
//        return $this->mailing->send_email($data);
        $person = $participant->getPerson();


        $s3 = new SesClient([
            'version' => 'latest',
            'region' => 'eu-west-1'
        ]);

        try {
            $s3->sendEmail([
                'Destination' => [
                    'ToAddresses' => [$person->getEmail()],
                ],
                'Message' => [
                    'Body' => [
                        'Html' => [
                            'Charset' => 'UTF-8',
                            'Data' => $this->twig->render(
                                'emails/hackaton.html.twig',
                                [
                                    'person' => $person,
                                    'url' => $this->generateUrl($participant)
                                ]
                            ),
                        ]
                    ],
                    'Subject' => [
                        'Charset' => 'UTF-8',
                        'Data' => $subject
                    ]
                ],
                'Source' => $this->from
            ]);
        } catch (SesException $error) {
            $this->logger->error($error->getAwsErrorMessage());
        }
    }

    private function generateUrl(Participant $participant)
    {
        return $this->generator->generate(
            'participant_activate',
            [ParticipantController::ACTIVATION_PARAM => $participant->getActivationCode()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}