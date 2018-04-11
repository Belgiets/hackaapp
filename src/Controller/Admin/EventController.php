<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Form\EventType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class EventController
 * @package App\Controller\Admin
 *
 * @Route("/admin/event")
 */
class EventController extends Controller
{
    /**
     * @Route("", name="event_list")
     * @Method({"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        return $this->render(
            'admin/event/listEvents.html.twig',
            [
                'items' => $this->getDoctrine()->getRepository(Event::class)->findAll()
            ]
        );
    }

    /**
     * @Route("/new", name="event_new")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
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
            'admin/event/newEdit.html.twig',
            [
                'form' => $form->createView(),
                'edit' => false
            ]
        );
    }
}
