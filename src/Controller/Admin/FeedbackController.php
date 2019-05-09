<?php

namespace App\Controller\Admin;

use App\Entity\Feedback;
use App\Entity\Participant\HackathonParticipant;
use App\Form\FeedbackType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/feedback")
 * @IsGranted("ROLE_ADMIN")
 */
class FeedbackController extends AbstractController
{
//    /**
//     * @Route("/", name="feedback_index", methods="GET")
//     */
//    public function index(FeedbackRepository $feedbackRepository): Response
//    {
//        return $this->render('feedback/index.html.twig', ['feedback' => $feedbackRepository->findAll()]);
//    }

    /**
     * @Route("/new/{id}", name="feedback_new", methods={"GET","POST"})
     * @Security("is_granted('FB_NEW', participant)")
     */
    public function new(Request $request, HackathonParticipant $participant)
    {
        $user = $this->getUser();
        $feedback = new Feedback();
        $form = $this->createForm(
            FeedbackType::class,
            $feedback,
            [
                'participant' => $participant,
                'user' => $user,
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($feedback);
            $em->flush();

            $this->addFlash('success', "Feedback created");
            return $this->redirectToRoute('participant_list');
        }

        return $this->render(
            'admin/feedback/newEditSimple.html.twig',
            [
                'person_photo' => $participant->getPerson()->getPhoto(),
                'form' => $form->createView(),
                'title' => "New feedback",
                'home_path' => 'participant_list',
            ]
        );
    }

//    /**
//     * @Route("/{id}", name="feedback_show", methods="GET")
//     */
//    public function show(Feedback $feedback): Response
//    {
//        return $this->render('feedback/show.html.twig', ['feedback' => $feedback]);
//    }
//
    /**
     * @Route("/edit/{id}", name="feedback_edit", methods={"GET","POST"})
     * @Security("is_granted('FB_EDIT', feedback)")
     */
    public function edit(Request $request, Feedback $feedback)
    {
        $user = $this->getUser();
        $participant = $feedback->getParticipant();

        $form = $this->createForm(
            FeedbackType::class,
            $feedback,
            [
                'participant' => $participant,
                'user' => $user,
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "Feedback updated");
            return $this->redirectToRoute('participant_list');
        }

        return $this->render(
            'admin/feedback/newEditSimple.html.twig',
            [
                'person_photo' => $participant->getPerson()->getPhoto(),
                'form' => $form->createView(),
                'title' => "Edit feedback",
                'home_path' => 'participant_list',
            ]
        );
    }
//
//    /**
//     * @Route("/{id}", name="feedback_delete", methods="DELETE")
//     */
//    public function delete(Request $request, Feedback $feedback): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$feedback->getId(), $request->request->get('_token'))) {
//            $em = $this->getDoctrine()->getManager();
//            $em->remove($feedback);
//            $em->flush();
//        }
//
//        return $this->redirectToRoute('feedback_index');
//    }
}
