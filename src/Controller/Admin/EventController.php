<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\Participant;
use App\Form\EventType;
use App\Helper\PaginatorTrait;
use App\Repository\ParticipantRepository;
use App\Repository\TeamRepository;
use App\Service\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class EventController
 * @package App\Controller\Admin
 *
 * @Route("/admin/event")
 * @IsGranted("ROLE_SUPER_ADMIN")
 */
class EventController extends AbstractController
{
    use PaginatorTrait;

    /**
     * @var Notification
     */
    private $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * @Route("", name="event_list", methods={"GET"})
     */
    public function listAction(Request $request)
    {
        $events = $this->paginator->paginate(
            $this->getDoctrine()->getRepository(Event::class)->getAll(),
            $request->query->getInt('page', 1),
            $this->getParameter('knp_paginator.page_range')
        );

        return $this->render(
            'admin/event/listEvents.html.twig',
            [
                'title' => 'Events',
                'items' => $events
            ]
        );
    }

    /**
     * @Route("/new", name="event_new", methods={"GET","POST"})
     */
    public function newAction(Request $request)
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('event_list');
        }

        return $this->render(
            'admin/newEditSimple.html.twig',
            [
                'form' => $form->createView(),
                'title' => 'New event',
                'home_path' => 'event_list'
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="event_edit", methods={"GET","POST"})
     */
    public function editAction(Request $request, Event $event)
    {
        $form = $this->createForm(EventType::class, $event, ['edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('event_list');
        }

        return $this->render(
            'admin/newEditSimple.html.twig',
            [
                'form' => $form->createView(),
                'title' => 'Edit event',
                'home_path' => 'event_list'
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="event_delete", methods={"GET","POST"})
     */
    public function deleteAction(Request $request, Event $event)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($event);
        $em->flush();

        return $this->redirectToRoute('event_list');
    }

    /**
     * @Route("/{id}/notify", name="event_notify", methods={"GET","POST"})
     */
    public function notify(Event $event)
    {
        $resultQty = $this->notification->notifyByEvent($event);

        $this->addFlash('success', "Notified {$resultQty} participants");

        return $this->redirectToRoute('event_list');
    }

    /**
     * @IsGranted("ROLE_SUPER_ADMIN")
     * @Route("/{id}/notify-activated", name="event_notify_activated", methods={"GET","POST"})
     */
    public function notifyActivated(Event $event, ParticipantRepository $repository)
    {
        $activated = $repository->findBy(['event' => $event, 'isActive' => true]);

        /** @var Participant $participant */
        foreach ($activated as $participant) {
            $this->notification->notify(
                $participant->getEvent()->getTitle(),
                $participant->getPerson()->getEmail(),
                'emails/notifyActivated.html.twig'
            );
        }

        $this->addFlash('success', 'Notified activated participants');

        return $this->redirectToRoute('event_list');
    }

    /**
     * @IsGranted("ROLE_SUPER_ADMIN")
     * @Route("/{id}/notify-awarded", name="event_notify_awarded", methods={"GET","POST"})
     */
    public function notifyAwarded(Event $event, TeamRepository $repository)
    {
        $teams = $repository->findBy(['event' => $event, 'isAwardee' => true]);

        foreach ($teams as $team) {
            $participants = $team->getParticipants();

            foreach ($participants as $participant) {
                if ($participant->isActive()) {
                    $this->notification->notify(
                        $participant->getEvent()->getTitle(),
                        $participant->getPerson()->getEmail(),
                        'emails/notifyAwarded.html.twig'
                    );
                }
            }
        }

        $this->addFlash('success', "Notified awarded team's participants");

        return $this->redirectToRoute('event_list');
    }
}
