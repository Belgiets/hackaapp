<?php

namespace App\Controller\Admin;


use App\Entity\Event\BaseEvent;
use App\Entity\HackathonEvent;
use App\Service\CsvParser;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class ParseCsvController
 * @package App\Controller
 *
 * @Route("/admin/csv")
 * @IsGranted("ROLE_SUPER_ADMIN")
 */
class ParseCsvController extends AbstractController
{

    /**
     * @Route("/parse", name="parse_csv")
     *
     * @param Request $request
     * @param CsvParser $parser
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function parseCsv(Request $request, CsvParser $parser)
    {
        $form = $this->createFormBuilder()
            ->add('csvFile', FileType::class)
            ->add('event', EntityType::class, [
                'class' => BaseEvent::class,
                'choice_label' => 'title'
            ])
            ->add('parse', SubmitType::class, ['label' => 'Parse', 'attr' => ['class' => 'btn btn-primary']])
            ->getForm();
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $parser->parse($form['csvFile']->getData(), $form['event']->getData());

            $this->addFlash('success', "Imported {$result['success_count']} persons");
        }

        return $this->render('admin/parse_csv/parseCsv.html.twig', [
            'title' => 'Parse CSV',
            'form' => $form->createView(),
        ]);
    }
}
