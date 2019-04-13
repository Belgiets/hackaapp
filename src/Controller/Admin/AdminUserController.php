<?php

namespace App\Controller\Admin;

use App\Entity\User\AdminUser;
use App\Form\AdminUserType;
use App\Helper\PaginatorTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AdminUserController
 * @package App\Controller\Admin
 *
 * @Route("/admin/admin-user")
 * @IsGranted("ROLE_SUPER_ADMIN")
 */
class AdminUserController extends AbstractController
{
    use PaginatorTrait;

    /**
     * @Route("", name="admin_list", methods={"GET"})
     */
    public function listAction(Request $request)
    {
        $adminUsers = $this->paginator->paginate(
            $this->getDoctrine()->getRepository(AdminUser::class)->getAll(),
            $request->query->getInt('page', 1),
            $this->getParameter('knp_paginator.page_range')
        );

        return $this->render(
            'admin/admin_user/list.html.twig',
            [
                'title' => 'Mentors',
                'adminUsers' => $adminUsers
            ]
        );
    }

    /**
     * @Route("/new", name="admin_new", methods={"GET","POST"})
     */
    public function newAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $admin = new AdminUser();

        $form = $this->createForm(AdminUserType::class, $admin, ['password_encoder' => $encoder]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($admin);
            $em->flush();

            return $this->redirectToRoute('admin_list');
        }

        return $this->render(
            'admin/newEditSimple.html.twig',
            [
                'form'  => $form->createView(),
                'title' => 'New admin user',
                'home_path' => 'admin_list'
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="admin_edit", methods={"GET","POST"})
     */
    public function editAction(Request $request, UserPasswordEncoderInterface $encoder, AdminUser $admin)
    {
        $form = $this->createForm(AdminUserType::class, $admin, ['password_encoder' => $encoder, 'edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('admin_list');
        }

        return $this->render(
            'admin/newEditSimple.html.twig',
            [
                'form'  => $form->createView(),
                'title' => 'Edit admin user',
                'home_path' => 'admin_list'
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="admin_delete", methods={"GET","POST"})
     */
    public function deleteAction(Request $request, AdminUser $admin)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($admin);
        $em->flush();

        return $this->redirectToRoute('admin_list');
    }
}
