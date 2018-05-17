<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use App\Entity\Participant;
use App\Form\MediaType;
use App\Form\ParticipantType;
use App\Helper\PaginatorTrait;
use App\Repository\ParticipantRepository;
use App\Repository\PersonRepository;
use App\Service\Notification;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/admin/participant")
 */
class ParticipantController extends Controller
{
    use PaginatorTrait;

    const ACTIVATION_PARAM = 'code';

    /**
     * @Route("", name="participant_list")
     * @Method({"GET"})
     */
    public function listAction(Request $request, ParticipantRepository $repository)
    {
        $participants = $this->paginator->paginate(
            $repository->getAll(),
            $request->query->getInt('page', 1),
            $this->getParameter('knp_paginator.page_range')
        );

        return $this->render(
            'admin/participant/listParticipants.html.twig',
            [
                'title' => 'Participants',
                'items' => $participants
            ]
        );
    }

    /**
     * @Route("/activate", name="participant_activate")
     */
    public function activate(Request $request)
    {
        if ($code = $request->query->get(self::ACTIVATION_PARAM)) {
            /** @var $em \Doctrine\Common\Persistence\ObjectManager */
            $em = $entityManager = $this->getDoctrine()->getManager();

            $participant = $em->getRepository(Participant::class)->findOneBy(['activationCode' => $code]);

            if ($participant) {
                $person = $participant->getPerson();
                $photo = $person->getPhoto() ? $participant->getPerson()->getPhoto() : new Media();

                $form = $this->createForm(MediaType::class, $photo, ['edit' => true]);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $em->persist($person);
                    $em->flush();
                }

                if ($participant->isActive()) {
                    $status = 'Already activated';

                    $this->addFlash(
                        'info',
                        $status
                    );
                } else {
                    $participant->activate();
                    $status = 'Activated';

                    $this->addFlash(
                        'success',
                        $status
                    );

                    $em->persist($participant);
                    $em->flush();
                }

                return $this->render('admin/participant/activate.html.twig', [
                    'status' => $status,
                    'participant' => $participant,
                    'form' => $form->createView()
                ]);
            } else {
                throw new NotFoundHttpException("Not valid activation code");
            }
        } else {
            throw new HttpException(400, 'Bad request.');
        }
    }

    /**
     * @Route("/notify", name="participant_notify")
     * @Method({"GET", "POST"})
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function notifyAction(Request $request, ParticipantRepository $repository, Notification $notification)
    {
        $form = $this->createFormBuilder()
            ->setMethod('POST')
            ->setAction($this->generateUrl('participant_notify'))
            ->add('participants', HiddenType::class)
            ->getForm();

        if ($request->getMethod() == Request::METHOD_GET) {
            return $this->render('admin/participant/notifyForm.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        $form->handleRequest($request);

        if ($form->isValid()) {
            $participantsIds = json_decode($form['participants']->getData());

            foreach ($participantsIds as $participantId) {
                /** @var Participant $participant */
                $participant = $repository->findOneBy(['id' => $participantId]);

                $notification->notify($participant->getEvent()->getTitle(), $participant);
                $participant->setIsNotified(true);

                $em = $this->getDoctrine()->getManager();
                $em->persist($participant);
                $em->flush();
            }

            return $this->redirectToRoute('participant_list');
        }

        return $this->render('admin/participant/notifyForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="participant_new", methods="GET|POST")
     */
    public function new(Request $request, SessionInterface $session, PersonRepository $personRepository)
    {
        $participant = new Participant();
        $form = $this->createForm(ParticipantType::class, $participant, [
            'person_id' => $session->get('person_id')
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($participant);
            $em->flush();

            return $this->redirectToRoute('participant_list');
        }

        return $this->render('admin/newEditSimple.html.twig', [
            'title' => "New participant",
            'home_path' => 'participant_list',
            'participant' => $participant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="participant_show", methods="GET")
     */
    public function show(Participant $participant)
    {
        return $this->render('participant/show.html.twig', ['participant' => $participant]);
    }

    /**
     * @Route("/{id}/edit", name="participant_edit", methods="GET|POST")
     */
    public function edit(Request $request, Participant $participant)
    {
        $form = $this->createForm(ParticipantType::class, $participant, [
            'edit' => true
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash("success", "Participant updated");

            return $this->redirectToRoute('participant_edit', ['id' => $participant->getId()]);
        }

        return $this->render('admin/participant/editPersonParticipant.html.twig', [
            'title' => "Edit person/participant",
            'home_path' => 'participant_list',
            'participant' => $participant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="participant_delete", methods="DELETE")
     */
    public function delete(Request $request, Participant $participant)
    {
        if ($this->isCsrfTokenValid('delete'.$participant->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($participant);
            $em->flush();
        }

        return $this->redirectToRoute('participant_index');
    }
}
