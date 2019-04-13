<?php

namespace App\Controller\Admin;

use App\Entity\Technology;
use App\Form\TechnologyType;
use App\Helper\PaginatorTrait;
use App\Repository\TechnologyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_SUPER_ADMIN")
 * @Route("/admin/technology")
 */
class TechnologyController extends AbstractController
{
    use PaginatorTrait;

    /**
     * @Route("/", name="technology_list", methods="GET")
     */
    public function index(Request $request, TechnologyRepository $technologyRepository)
    {
        $technologies = $this->paginator->paginate(
            $technologyRepository->getAll(),
            $request->query->getInt('page', 1),
            $this->getParameter('knp_paginator.page_range')
        );

        return $this->render(
            'admin/technology/listTechnologies.html.twig',
            [
                'title' => 'Technologies',
                'items' => $technologies
            ]
        );
    }

    /**
     * @Route("/new", name="technology_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $technology = new Technology();
        $form = $this->createForm(TechnologyType::class, $technology);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($technology);
            $em->flush();

            $this->addFlash('success', 'Technology created');

            return $this->redirectToRoute('technology_list');
        }

        return $this->render('admin/newEditSimple.html.twig', [
            'home_path' => 'technology_list',
            'title' => 'New technology',
            'technology' => $technology,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="technology_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Technology $technology): Response
    {
        $form = $this->createForm(TechnologyType::class, $technology);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Technology updated');

            return $this->redirectToRoute('technology_edit', ['id' => $technology->getId()]);
        }

        return $this->render('admin/newEditSimple.html.twig', [
            'home_path' => 'technology_list',
            'title' => 'Edit technology',
            'technology' => $technology,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="technology_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Technology $technology): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($technology);
        $em->flush();

        $this->addFlash('success', "Technology removed");

        return $this->redirectToRoute('technology_list');
    }
}
