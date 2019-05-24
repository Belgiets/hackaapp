<?php

namespace App\Controller\Admin;

use App\Entity\Team;
use App\Form\TeamType;
use App\Helper\PaginatorTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class TeamController
 * @package App\Controller\Admin
 *
 * @Route("/admin/team")
 */
class TeamController extends AbstractController
{
    use PaginatorTrait;

    /**
     * @Route("", name="team_list", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function listAction(Request $request)
    {
        $teams = $this->paginator->paginate(
            $this->getDoctrine()->getRepository(Team::class)->getAll(),
            $request->query->getInt('page', 1),
            $this->getParameter('knp_paginator.page_range'),
            ['defaultSortFieldName' => 't.name', 'defaultSortDirection' => 'asc']

        );

        return $this->render(
            'admin/team/listTeams.html.twig',
            [
                'title' => 'Teams',
                'items' => $teams
            ]
        );
    }

    /**
     * @Route("/new", name="team_new", methods={"GET","POST"})
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function newAction(Request $request)
    {
        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($team);
            $em->flush();

            return $this->redirectToRoute('team_list');
        }

        return $this->render(
            'admin/newEditSimple.html.twig',
            [
                'form' => $form->createView(),
                'title' => 'New team',
                'home_path' => 'team_list'
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="team_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function editAction(Request $request, Team $team)
    {
        $form = $this->createForm(TeamType::class, $team, ['edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('team_list');
        }

        return $this->render(
            'admin/newEditSimple.html.twig',
            [
                'form' => $form->createView(),
                'title' => 'Edit team',
                'home_path' => 'team_list'
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="team_delete", methods={"GET","POST"})
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function deleteAction(Team $team)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($team);
        $em->flush();

        return $this->redirectToRoute('team_list');
    }
}
