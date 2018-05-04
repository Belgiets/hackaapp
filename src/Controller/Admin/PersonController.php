<?php

namespace App\Controller\Admin;

use App\Entity\Person;
use App\Form\PersonType;
use App\Helper\PaginatorTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class PersonController
 * @package App\Controller\Admin
 *
 * @Route("/admin/person")
 */
class PersonController extends Controller
{
    use PaginatorTrait;

    /**
     * @Route("", name="person_list")
     * @Method({"GET"})
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
     * @Route("/{id}/view", name="person_view")
     * @Method({"GET"})
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
     * @Route("/new", name="person_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();

            return $this->redirectToRoute('person_list');
        }

        return $this->render(
            'admin/newEditSimple.html.twig',
            [
                'form' => $form->createView(),
                'title' => 'New person',
                'home_path' => 'person_list'
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="person_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Person $person)
    {
        $form = $this->createForm(PersonType::class, $person, ['edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('person_list');
        }

        return $this->render(
            'admin/newEditSimple.html.twig',
            [
                'form' => $form->createView(),
                'title' => 'Edit person',
                'home_path' => 'person_list'
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="person_delete")
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, Person $event)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($event);
        $em->flush();

        return $this->redirectToRoute('person_list');
    }
}
