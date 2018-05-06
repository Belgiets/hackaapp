<?php


namespace App\Service;

use App\Entity\Person;
use Sendinblue\Mailin;
use Twig\Environment;


class Notification
{
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
     * Notification constructor.
     * @throws \Exception
     */
    public function __construct(string $from, string $apiKey, Environment $twig)
    {
        $this->from = $from;
        $this->mailing = new Mailin(self::ENDPOINT, $apiKey, self::TIMEOUT);;
        $this->twig = $twig;
    }

    /**
     * @param string $subject
     * @param Person $person
     * @return mixed
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function notify(string $subject, Person $person)
    {
        $data = [
            'from' => [$this->from, "from email!"],
            'to' => [$person->getEmail() => $person->getFirstName() . ' ' . $person->getLastName()],
            'subject' => $subject,
            'html' => $this->twig->render(
                'emails/hackaton.html.twig',
                ['person' => $person]
            ),
        ];

        return $this->mailing->send_email($data);
    }
}