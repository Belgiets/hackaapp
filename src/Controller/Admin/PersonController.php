<?php

namespace App\Controller\Admin;

use App\Entity\Person;
use App\Form\PersonType;
use App\Helper\PaginatorTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class PersonController
 * @package App\Controller\Admin
 *
 * @Route("/admin/person")
 * @IsGranted("ROLE_SUPER_ADMIN")
 */
class PersonController extends AbstractController
{
    use PaginatorTrait;

    /**
     * @Route("", name="person_list", methods={"GET"})
     */
    public function listAction(Request $request)
    {
        $persons = $this->paginator->paginate(
            $this->getDoctrine()->getRepository(Person::class)->getAll(),
            $request->query->getInt('page', 1),
            $this->getParameter('knp_paginator.page_range')
        );

        return $this->render(
            'admin/person/listPersons.html.twig',
            [
                'title' => 'Persons',
                'items' => $persons
            ]
        );
    }

    /**
     * @Route("/{id}/view", name="person_view", methods={"GET"})
     */
    public function viewAction(Person $person)
    {
        return $this->render(
            'admin/person/viewPerson.html.twig',
            [
                'person' => $person
            ]
        );
    }

    /**
     * @Route("/new", name="person_new", methods={"GET","POST"})
     */
    public function newAction(Request $request, SessionInterface $session)
    {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();

            $session->set('person_id', $person->getId());

            return $this->redirectToRoute('participant_new');
        }

        return $this->render(
            'admin/person/newPerson.html.twig',
            [
                'form' => $form->createView(),
                'title' => 'New person/participant',
                'home_path' => 'person_list'
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="person_edit", methods={"GET","POST"})
     */
    public function editAction(Request $request, Person $person)
    {
        $form = $this->createForm(PersonType::class, $person, ['edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash("success", "Person updated");

            return $this->redirectToRoute('participant_list');
        }

        return $this->render(
            'admin/person/editPerson.html.twig',
            [
                'form' => $form->createView(),
                'title' => 'Edit person',
                'home_path' => 'person_list',
                'person' => $person
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="person_delete", methods={"GET","POST"})
     */
    public function deleteAction(Request $request, Person $event)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($event);
        $em->flush();

        return $this->redirectToRoute('person_list');
    }
}
