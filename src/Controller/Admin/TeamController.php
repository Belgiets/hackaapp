<?php

namespace App\Controller\Admin;

use App\Entity\Team;
use App\Form\TeamType;
use App\Helper\PaginatorTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class TeamController
 * @package App\Controller\Admin
 *
 * @Route("/admin/team")
 */
class TeamController extends Controller
{
    use PaginatorTrait;

    /**
     * @Route("", name="team_list")
     * @IsGranted("ROLE_ADMIN")
     * @Method({"GET"})
     */
    public function listAction(Request $request)
    {
        $teams = $this->paginator->paginate(
            $this->getDoctrine()->getRepository(Team::class)->getAll(),
            $request->query->getInt('page', 1),
            $this->getParameter('knp_paginator.page_range')
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
     * @Route("/new", name="team_new")
     * @IsGranted("ROLE_SUPER_ADMIN")
     * @Method({"GET", "POST"})
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
     * @Route("/{id}/edit", name="team_edit")
     * @IsGranted("ROLE_SUPER_ADMIN")
     * @Method({"GET", "POST"})
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
     * @Route("/{id}/delete", name="team_delete")
     * @IsGranted("ROLE_SUPER_ADMIN")
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, Team $team)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($team);
        $em->flush();

        return $this->redirectToRoute('team_list');
    }
}
