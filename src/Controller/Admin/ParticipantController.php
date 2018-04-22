<?php

namespace App\Controller\Admin;

use App\Entity\Participant;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class ParticipantController
 * @package App\Controller\Admin
 *
 * @Route("/admin/participant")
 */
class ParticipantController extends Controller
{
    const ACTIVATION_PARAM = 'code';

    /**
     * @Route("/activate", name="participant_activate")
     */
    public function activate(Request $request)
    {
        if ($code = $request->query->get(self::ACTIVATION_PARAM)) {
            /** @var $em \Doctrine\Common\Persistence\ObjectManager */
            $em = $entityManager = $this->getDoctrine()->getManager();

            $participant = $em->getRepository(Participant::class)->findOneBy(['activationCode' => $code]);

            if ($participant) {
                if ($participant->isActive()) {
                    $status = 'already activated';
                } else {
                    $participant->activate();
                    $status = 'activated';

                    $em->persist($participant);
                    $em->flush();
                }

                return $this->render('admin/participant/activate.html.twig', [
                    'status' => $status,
                    'participant' => $participant
                ]);
            } else {
                throw new NotFoundHttpException("Not valid activation code");
            }
        } else {
            throw new HttpException(400, 'Bad request.');
        }
    }

    /**
     * @Route("/{id}/activation-link", name="participant_activate_link")
     */
    public function activationLink(Participant $participant)
    {
        $url = $this->generateUrl(
            'participant_activate',
            [self::ACTIVATION_PARAM => $participant->getActivationCode()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return $this->render('admin/participant/activate-link.html.twig', [
            'url' => $url
        ]);
    }
}
