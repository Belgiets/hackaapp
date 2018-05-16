<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Form\EventType;
use App\Helper\PaginatorTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class EventController
 * @package App\Controller\Admin
 *
 * @Route("/admin/event")
 * @IsGranted("ROLE_SUPER_ADMIN")
 */
class EventController extends Controller
{
    use PaginatorTrait;

    /**
     * @Route("", name="event_list")
     * @Method({"GET"})
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
     * @Route("/new", name="event_new")
     * @Method({"GET", "POST"})
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
     * @Route("/{id}/edit", name="event_edit")
     * @Method({"GET", "POST"})
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
     * @Route("/{id}/delete", name="event_delete")
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, Event $event)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($event);
        $em->flush();

        return $this->redirectToRoute('event_list');
    }
}
