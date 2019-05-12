<?php


namespace App\Service;

use App\Helper\LoggerTrait;
use Aws\Ses\Exception\SesException;
use Aws\Ses\SesClient;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;


class Notification
{
    use LoggerTrait;

    /**
     * @var string
     */
    private $from;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var UrlGenerator
     */
    private $generator;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $em;

    /**
     * Notification constructor.
     *
     * @throws \Exception
     */
    public function __construct(
        string $from,
        string $apiKey,
        Environment $twig,
        UrlGeneratorInterface $generator,
        ManagerRegistry $doctrine
    ) {
        $this->from = $from;
        $this->twig = $twig;
        $this->generator = $generator;
        $this->em = $doctrine->getManager();
    }

    /**
     * @param string $to
     * @param string $title
     * @param string $template
     * @param array $attrs
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    private function sendEmail($to, $title, $template, $attrs = [])
    {
        $s3 = new SesClient([
            'version' => 'latest',
            'region' => 'eu-west-1'
        ]);

        try {
            $s3->sendEmail([
                'Destination' => [
                    'ToAddresses' => [$to],
                ],
                'Message' => [
                    'Body' => [
                        'Html' => [
                            'Charset' => 'UTF-8',
                            'Data' => $this->twig->render($template, $attrs),
                        ]
                    ],
                    'Subject' => [
                        'Charset' => 'UTF-8',
                        'Data' => $title
                    ]
                ],
                'Source' => $this->from
            ]);
        } catch (SesException $error) {
            $this->logger->error($error->getAwsErrorMessage());
        }
    }

    /**
     * @param \App\Entity\Event $event
     * @return int
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function notifyByEvent($event)
    {
        $participants = $event->getParticipants();

        if (!empty($participants)) {
            foreach ($participants->toArray() as $participant) {
                $person = $participant->getPerson();

                $this->sendEmail(
                    $person->getEmail(),
                    $event->getTitle(),
                    'emails/hackaton.html.twig',
                    [
                        'person' => $person,
                        'qr_url' => $participant->getActivationQr()->getUrl(),
                    ]
                );

                $participant->setIsNotified(true);
                $this->em->persist($participant);
                $this->em->flush();
            }
        }

        return count($participants);
    }

//    /**
//     * @param string $subject
//     * @param Participant $participant
//     * @throws \Twig_Error_Loader
//     * @throws \Twig_Error_Runtime
//     * @throws \Twig_Error_Syntax
//     */
//    public function notify(string $subject, Participant $participant)
//    {
//        $person = $participant->getPerson();
//
//
//        $s3 = new SesClient([
//            'version' => 'latest',
//            'region' => 'eu-west-1'
//        ]);
//
//        try {
//            $s3->sendEmail([
//                'Destination' => [
//                    'ToAddresses' => [$person->getEmail()],
//                ],
//                'Message' => [
//                    'Body' => [
//                        'Html' => [
//                            'Charset' => 'UTF-8',
//                            'Data' => $this->twig->render(
//                                'emails/hackaton.html.twig',
//                                [
//                                    'person' => $person,
//                                    'qr_url' => $participant->getActivationQr()->getUrl()
//                                ]
//                            ),
//                        ]
//                    ],
//                    'Subject' => [
//                        'Charset' => 'UTF-8',
//                        'Data' => $subject
//                    ]
//                ],
//                'Source' => $this->from
//            ]);
//        } catch (SesException $error) {
//            $this->logger->error($error->getAwsErrorMessage());
//        }
//    }

    /**
     * @param string $subject
     * @param string $email
     * @param string $template
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function notify(string $subject, string $email, string $template)
    {
        $this->sendEmail($email, $subject, $template);
    }
}