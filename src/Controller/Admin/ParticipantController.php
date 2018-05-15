<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use App\Entity\Participant;
use App\Form\MediaType;
use App\Helper\PaginatorTrait;
use App\Repository\ParticipantRepository;
use App\Service\Notification;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class ParticipantController
 * @package App\Controller\Admin
 *
 * @Route("/admin/participant")
 */
class ParticipantController extends Controller
{
    use PaginatorTrait;

    const ACTIVATION_PARAM = 'code';

    /**
     * @Route("", name="participant_list")
     * @Method({"GET"})
     */
    public function listAction(Request $request, ParticipantRepository $repository)
    {
        $participants = $this->paginator->paginate(
            $repository->getAll(),
            $request->query->getInt('page', 1),
            $this->getParameter('knp_paginator.page_range')
        );

        return $this->render(
            'admin/participant/listParticipants.html.twig',
            [
                'title' => 'Participants',
                'items' => $participants
            ]
        );
    }

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
                $person = $participant->getPerson();
                $photo = $person->getPhoto() ? $participant->getPerson()->getPhoto() : new Media();

                $form = $this->createForm(MediaType::class, $photo, ['edit' => true]);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $em->persist($person);
                    $em->flush();
                }

                if ($participant->isActive()) {
                    $status = 'Already activated';

                    $this->addFlash(
                        'info',
                        $status
                    );
                } else {
                    $participant->activate();
                    $status = 'Activated';

                    $this->addFlash(
                        'success',
                        $status
                    );

                    $em->persist($participant);
                    $em->flush();
                }

                return $this->render('admin/participant/activate.html.twig', [
                    'status' => $status,
                    'participant' => $participant,
                    'form' => $form->createView()
                ]);
            } else {
                throw new NotFoundHttpException("Not valid activation code");
            }
        } else {
            throw new HttpException(400, 'Bad request.');
        }
    }

    /**
     * @Route("/notify", name="participant_notify")
     * @Method({"GET", "POST"})
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function notifyAction(Request $request, ParticipantRepository $repository, Notification $notification)
    {
        $form = $this->createFormBuilder()
            ->setMethod('POST')
            ->setAction($this->generateUrl('participant_notify'))
            ->add('participants', HiddenType::class)
            ->getForm();

        if ($request->getMethod() == Request::METHOD_GET) {
            return $this->render('admin/participant/notifyForm.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        $form->handleRequest($request);

        if ($form->isValid()) {
            $participantsIds = json_decode($form['participants']->getData());

            foreach ($participantsIds as $participantId) {
                /** @var Participant $participant */
                $participant = $repository->findOneBy(['id' => $participantId]);

                $notification->notify($participant->getEvent()->getTitle(), $participant);
                $participant->setIsNotified(true);

                $em = $this->getDoctrine()->getManager();
                $em->persist($participant);
                $em->flush();
            }

            return $this->redirectToRoute('participant_list');
        }

        return $this->render('admin/participant/notifyForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
