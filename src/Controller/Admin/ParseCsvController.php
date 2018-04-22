<?php

namespace App\Controller\Admin;


use App\Entity\Event;
use App\Service\CsvParser;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class ParseCsvController
 * @package App\Controller
 *
 * @Route("/admin/csv")
 */
class ParseCsvController extends Controller
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
                'class' => Event::class,
                'choice_label' => 'title'
            ])
            ->add('parse', SubmitType::class, ['label' => 'Parse'])
            ->getForm();
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $parser->parse($form['csvFile']->getData(), $form['event']->getData());
        }

        return $this->render('admin/parse_csv/parseCsv.html.twig', [
            'result' => empty($result) ? '' : $result,
            'form' => $form->createView(),
        ]);
    }
}
